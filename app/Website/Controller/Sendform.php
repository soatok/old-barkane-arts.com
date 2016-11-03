<?php
declare(strict_types=1);
namespace BarkaneArts\Website\Controller;

use \ParagonIE\GPGMailer\GPGMailer;
use \Zend\Mail\Message;
use \Zend\Mail\Transport\Sendmail;

$validator= new Zend\Validator\EmailAddress();

if($validator->isValid($_POST['email']))
{
    $message = new Message;
    $message->setBodyText(strip_tags($_POST['message']));
    $message->setBodyHtml($_POST['message']);
    $message->setFrom($_POST['email'],$_POST['name']);
    $message->addTo('djerale@gmail.com','David Jerale');
    $message->setSubject($_POST['subject'] . " -Barkane Arts");


    $gpgMailer = new GPGMailer(
        new Sendmail(),
        ['homedir' => '/homedir/containing/keyring']
    );
    $fingerprint = '052EC4EF72EF66F3BBE3C74385A4C89D9BDFA52D';
    $gpgMailer->send($message, $fingerprint);

    echo "Message sent";
}
else
{
// invalid email
    foreach($validator->getMessages() as $errorMessage)
    {
        echo "$errorMessage<br/>";
    }
}
/**
 * Created by PhpStorm.
 * User: david
 * Date: 11/2/16
 * Time: 9:28 PM
 */
