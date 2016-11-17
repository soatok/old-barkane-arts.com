<?php
declare(strict_types=1);
namespace BarkaneArts\Website\Controller;

use BarkaneArts\Framework\Controller;
use ParagonIE\GPGMailer\GPGMailer;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Validator\EmailAddress;

class Sendform extends Controller
{
    public function contact()
    {
        $validator= new EmailAddress();

        if ($post = $this->getPostData()) {
            if ($validator->isValid($post['email'])) {
                $message = new Message;
                $message->setBody($post['message']);
                $message->setFrom($post['email'], $post['name']);
                $message->addTo('djerale@gmail.com', 'David Jerale');
                $message->setSubject($post['subject'] . " -Barkane Arts");


                $gpgMailer = new GPGMailer(
                    new Sendmail(),
                    ['homedir' => DATA_DIR]
                );
                $fingerprint = '052EC4EF72EF66F3BBE3C74385A4C89D9BDFA52D';

                /**
                 * RUN THIS ONCE - It imports the public key.
                 */
                try {
                    $exported = $gpgMailer->export($fingerprint);
                } catch (\Crypt_GPG_KeyNotFoundException $ex) {
                    $gpgMailer->import(
                        "----- BEGIN PGP PUBLIC KEY -----

PUT IT HERE

----- END PGP PUBLIC KEY -----"
                    );
                }

                $gpgMailer->send($message, $fingerprint);

                echo "Message sent";
            } else {
                $this->twig->addGlobal(
                    'error_messages',
                    $validator->getMessages()
                );
            }
        }
        $this->view('contact/contact');
    }
}
