<?php
use PHPUnit\Framework\TestCase;

require_once "./qd_simple_mail.php";

class SimpleMailTest extends TestCase
{
    public function testQdSimpleMail()
    {
        $mail = new QdSimpleMail();
        $mail->debug = 2;
        $mail->to("to@example.com", null);
        $mail->from("from@example.com", null);
        $mail->subject("A subject");
        $mail->content("body");

        $result = "<pre>To: to@example.com\r\nSubject: A subject\r\nFrom: from@example.com\r\nContent-Transfer-Encoding: 7bit\r\nContent-Type: text/plain; charset=iso-2022-jp\r\nMIME-Version: 1.0\r\nX-mailer: QdSimplemail 0.1.0a\r\n\r\nbody\r\n</pre>";
        $this->expectOutputString($result);

        $mail->send();
    }
}
