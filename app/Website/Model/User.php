<?php
declare(strict_types=1);
namespace BarkaneArts\Website\Model;

use BarkaneArts\Framework\Alerts\UserAuthenticationFailed;
use BarkaneArts\Framework\Model;
use ParagonIE\EasyDB\EasyStatement;
use ParagonIE\Halite\HiddenString;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Password;
use ParagonIE\Halite\Symmetric\EncryptionKey;

/**
 * Class User
 * @package BarkaneArts\Website\Model
 */
class User extends Model
{
    /**
     * Attempt to authenticate
     *
     * @param string $username
     * @param HiddenString $password
     * @return array
     * @throws UserAuthenticationFailed
     */
    public function login(string $username, HiddenString $password): array
    {
        $where = EasyStatement::open()
            ->with('username = ?', $username);
        $user = $this->db->row(
            "SELECT * FROM barkane_users WHERE {$where}",
            $where->values()
        );
        if (!empty($user)) {
            if (Password::verify(
                $password,
                $user['passphrase'],
                $this->loadEncryptionKey()
            )) {
                return $user;
            }
        }
        throw new UserAuthenticationFailed();
    }

    /**
     * @param array $userData
     * @return bool
     */
    public function create(array $userData = []): bool
    {
        $this->db->beginTransaction();
        $this->db->insert(
            'barkane_users',
            [
                'username' =>
                    $userData['username'],
                'passphrase' =>
                    Password::hash(
                        new HiddenString($userData['passphrase']),
                        $this->loadEncryptionKey()
                    )
            ]
        );

        return $this->db->commit();
    }

    /**
     * @return EncryptionKey
     */
    protected function loadEncryptionKey(): EncryptionKey
    {
        return KeyFactory::loadEncryptionKey(DATA_DIR . '/users.key');
    }

    /**
     *
     */
    public function install()
    {
        $this->db->query("CREATE TABLE barkane_users (
            userid BIGSERIAL PRIMARY KEY,
            username TEXT,
            passphrase TEXT
        )");
    }
}
