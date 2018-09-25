<?php

namespace Tests;

use MammutAlex\LardiTrans\Exception\ApiAuthException;
use MammutAlex\LardiTrans\Exception\ApiMethodException;
use PHPUnit\Framework\TestCase;
use MammutAlex\LardiTrans\LardiTrans;

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
        $data = $client->sendMethod('test', ['test_text' => 'Привет']);
        $this->assertSame('Привет', $data['test_text']);
    }

    public function testSidAuthError()
    {
        $this->expectException(ApiAuthException::class);
        $client = new LardiTrans();
        $client->setSig('fake_sid');
        $client->sendTestSig();
    }

    public function testApiMethodError()
    {
        $this->expectException(ApiMethodException::class);
        $client = new LardiTrans();
        $client->sendMethod('error.method.test');
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