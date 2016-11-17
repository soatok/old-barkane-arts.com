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
                        "-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v1

mQENBFgaswYBCADCPDsUI5Av4/xwChVumGbVhaXqLncGb3JLtRL7XUafhpSjp0hD
7Z/9zLwKXavzGpKJ+S3TTRocsaiEeGO04MXXqTHmxIMsVT2G1xxgnYE/oiyRULaD
KP8eR72um4i2nORitwuFn19LiO03wssnSwlYqPU3Ci6NEurMIkjrzhqMqRSm26RC
kbPty/RioaVVSNu4t3hT4wpBmf2Zy0nLC8XHQspwvDKnJrsCWrFNM0tXJsgQB3i0
VHXIUb/M5XYRWFJd8eQkPL7+7Wtal0VTscYW8Qns/JtqkeCJF+qSybUKFVj76wkZ
gb39Uc4Yi8jJHG2zEn+z0Ber6Bxf5VXMwzDfABEBAAG0NERhdmlkIEplcmFsZSAo
UnVkb2xmIFdoaXRlY2hlc3QpIDxkamVyYWxlQGdtYWlsLmNvbT6JATgEEwECACIF
AlgaswYCGwMGCwkIBwMCBhUIAgkKCwQWAgMBAh4BAheAAAoJEIWkyJ2b36UtTgwI
AL7FLh+m61QpzmHxaRGYfxUrMQAfkXGCYWLqyRrXR2YJnvv7WvM1fJVsIanT4pC3
Fg4joCkmw6EpOrWZ12tpYApYv4Fuat/qLl6FBfKtpU23Cqd/2c4Bbp7ag4Q+jiwb
bYIjkwEMSkSyitWOmAC0GB+vCaxUWynS7oQkDRFQWvQ2QphEEdBfgMMiDRLoSyv3
5ZPq6mT7fYFEqpIFUq+b7NfvKsXapMYT+y3sNkdyXmUFWlfRdzoadueuDf02VzMh
lu1Q7bhxo1mq64qsPnc5z8vnoi/6H4YkU7d5O2BuP1DeuQT1TQzsE747k1kFyvlB
6I8dSTcqdzigZm7VdEEKd9e5AQ0EWBqzBgEIAL2+kPO2tgZldVZIZPD8yEwdJ0GX
bAStq8LL9yNQHtgGX2e4jXVdISrn3dS6XAHeUvWO16W1qG9ej6n6IzxSRJKN51IO
IuasqCpNQlZ2gBiF+WM0r2Vqnchqa+Gx/5WKhLbVGY7hHisgxmcd6cznOU/qOgmX
OrQAiM1bA/JQqdHnVv9X1cmSspDn3V5qVWZ+i/jF4Gqa7Pgv9MU79bcdJxYT0RJH
Ow08kTUDFMwkkMR1IgY4A0BO/DqEmUgC8d12/qxQNjVdzeeAqNuRr7PWOKLeJXbH
uJpBEr1MEhsxlm1p67AolBq0YlhljFWa9UMjBCZbTTHyWkFL9IrerF4zZHkAEQEA
AYkBHwQYAQIACQUCWBqzBgIbDAAKCRCFpMidm9+lLbd5CACq8ArbFSBVsMOoMhhq
RQgVl07rG2niCU2bTWHfpP1QULxwF0nT9fFrJvnPPjmu60yDOd56cDF/IVfgud8+
U6qn15i4LJ2j+7DHAJ6RQwQSFUMef/TLzWLK7ytTvKkcrUdWkMcfDT/qIPiszgAG
4NeyRli8cxFG1cu/K9KuD28IYxaBEk7YbTmyJlA5mP9SknMI4sykpQjLHtrixKyv
kHRHbiR5FvhEKd4AIN8rfkmVa2ek+Go6A6V4XdST/yHS8206sgneylHSq1kQyGyE
aL3YVCRXW/ZkZNEIfXOHk9gxR8isUAu1d/fBQFxlKdqvl8VuBAUAtyEN24FPTpQD
x6b6
=JvUL
-----END PGP PUBLIC KEY BLOCK-----"
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
