<?php
use PHPUnit\Framework\TestCase;

require_once "./qdmail_receiver.php";

class QdmailReceiverTest extends TestCase
{
    public function testQdPop()
    {
        $instance = new QdPop();

        // Todo: Implement new debug function.
    }
}
