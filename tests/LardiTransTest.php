<?php

namespace Tests;

use MammutAlex\LardiTrans\Method;
use PHPUnit\Framework\TestCase;
use MammutAlex\LardiTrans\LardiTrans;
use MammutAlex\LardiTrans\Exception\ApiErrorException;
use MammutAlex\LardiTrans\Exception\LocalValidateException;
use MammutAlex\LardiTrans\Exception\MethodNotFoundException;

final class LardiTransTest extends TestCase
{
    public function testSendSystemTestRequest()
    {
        $client = new LardiTrans();
        $data = $client->test(['test_text' => 'Привет']);
        $this->assertSame('Привет', $data['test_text']);
    }
    public function testSendSystemTestRequestInMetod()
    {
        $client = new LardiTrans();
        $data = $client->callMethod('test',['test_text' => 'Привет']);
        $this->assertSame('Привет', $data['test_text']);
    }

    public function testSendSystemTestSidRequestLocalValidateError()
    {
        $this->expectException(LocalValidateException::class);
        $client = new LardiTrans();
        $client->testSig(['fake_value' => 'Привет']);
    }

    public function testSendSystemTestSidRequestAuthError()
    {
        $this->expectException(ApiErrorException::class);
        $client = new LardiTrans();
        $client->testSig(['sig' => 'fake_sid']);
    }

    public function testSendToUndefinedMethod()
    {
        $this->expectException(MethodNotFoundException::class);
        $client = new LardiTrans();
        $client->testFake(['sig' => 'fake_sid']);
    }
    public function testSendToUndefinedMethodInNodDynamic()
    {
        $this->expectException(MethodNotFoundException::class);
        $client = new LardiTrans();
        $client->testFake(['sig' => 'fake_sid']);
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

    public function testGetMethods()
    {
        $client = new LardiTrans();
        $client->setMethod('testMethod', new Method());
        foreach ($client->getMethods() as $method) {
            $this->assertInstanceOf(Method::class, $method);
        }
    }
}