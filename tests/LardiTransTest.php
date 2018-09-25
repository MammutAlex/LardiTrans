<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use MammutAlex\LardiTrans\LardiTrans;
use MammutAlex\LardiTrans\Exception\ApiErrorException;

final class LardiTransTest extends TestCase
{
    public function testSendSystemTestRequest()
    {
        $client = new LardiTrans();
        $data = $client->sendTest('Привет');
        $this->assertSame('Привет', $data['test_text']);
    }
    public function testSendSystemTestRequestInMetod()
    {
        $client = new LardiTrans();
        $data = $client->callMethod('test',['test_text' => 'Привет']);
        $this->assertSame('Привет', $data['test_text']);
    }

    public function testSendSystemTestSidRequestAuthError()
    {
        $this->expectException(ApiErrorException::class);
        $client = new LardiTrans();
        $client->setSig('fake_sid');
        $client->sendTestSig();
    }

    public function testAddAndGetSig()
    {
        $client = new LardiTrans();
        $client->setSig('test sig');
        $this->assertSame('test sig', $client->getSig());
    }

    public function testAddAndGetUid()
    {
        $client = new LardiTrans();
        $client->setUid('test uid');
        $this->assertSame('test uid', $client->getUid());
    }
}