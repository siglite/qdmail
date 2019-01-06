<?php
use PHPUnit\Framework\TestCase;

require_once "./qdmail.php";

class QdmailTest extends TestCase
{
    public function testQdmail()
    {
        $mail = new Qdmail();
        $mail->debug = 2;

        # Todo: Implement new debug function.
        #       (QdmailBase#debugEcho() include environment-dependent strings)

        #$this->expectOutputString("");
        #$mail->easyText(
        #    array("to@example.com"),
        #    "A subject",
        #    "body",
        #    array("from@example.com")
        #);
    }
}
