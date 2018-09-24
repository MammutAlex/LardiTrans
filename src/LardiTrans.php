<?php

namespace MammutAlex\LardiTrans;

use MammutAlex\LardiTrans\Exception\MethodNotFoundException;

/**
 * Class LardiTrans
 * @package MammutAlex\LardiTrans
 */
final class LardiTrans
{
    private $sig;
    private $uid;

    private $methods = [];
    private $apiClient;

    /**
     * LardiTrans constructor.
     */
    public function __construct()
    {
        $this->apiClient = new ApiClient();
        $this->setDefaultMethods();
    }

    /**
     * @param string $name
     * @param Method $method
     *
     * @return LardiTrans
     */
    public function setMethod(string $name, Method $method): self
    {
        $this->methods[$name] = $method;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return array
     * @throws Exception\ApiErrorException
     * @throws Exception\LocalValidateException
     * @throws MethodNotFoundException
     */
    public function __call(string $name, array $arguments): array
    {
        return $this->callMethod($name, $arguments[0]);
    }

    /**
     * @param string $name
     * @param array  $parameters
     *
     * @return array
     * @throws Exception\ApiErrorException
     * @throws Exception\LocalValidateException
     * @throws MethodNotFoundException
     */
    public function callMethod(string $name, array $parameters): array
    {
        if (isset($this->methods[$name])) {
            return $this->apiClient->requestCreator($this->methods[$name], $parameters);
        }
        throw new MethodNotFoundException('Api has not method ' . $name);
    }

    /**
     * @return mixed
     */
    public function getSig()
    {
        return $this->sig;
    }

    /**
     * @param string $sid
     *
     * @return LardiTrans
     */
    public function setSig(string $sid): self
    {
        $this->sig = $sid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     *
     * @return LardiTrans
     */
    public function setUid(string $uid): self
    {
        $this->uid = $uid;
        return $this;
    }

    private function setDefaultMethods()
    {
        $this
            /**
             * @link http://api.lardi-trans.com/doc/test
             */
            ->setMethod('test', new Method('test', [
                'test_text'
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/test.sig
             */
            ->setMethod('testSig', new Method('test.sig', [
                'sig'
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/auth
             */
            ->setMethod('auth', new Method('auth', [
                'login',
                'password'
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/partners.info.get
             */
            ->setMethod('partnersInfoGet', new Method('partners.info.get', [
                'sig'
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/partners.info.get
             */
            ->setMethod('usersFirmInfo', new Method('users.firm.info', [
                'sig',
                'uid'
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/user.set.status
             */
            ->setMethod('userSetStatus', new Method('user.set.status', [
                'sig',
                'status',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/base.country
             */
            ->setMethod('baseCountry', new Method('base.country', [
                'sig'
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/base.auto_tip
             */
            ->setMethod('baseAutoTip', new Method('base.auto_tip', [
                'sig'
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/base.zagruz
             */
            ->setMethod('baseZagruz', new Method('base.zagruz', [
                'sig'
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.add
             */
            ->setMethod('myGruzAdd', new Method('my.gruz.add', [
                'sig',
                'country_from_id',
                'area_from_id',
                'city_from',
                'country_to_id',
                'city_to',
                'date_from',
                'gruz',
                'body_type_group_id',
                'mass',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.trans.add
             */
            ->setMethod('myTransAdd', new Method('my.trans.add', [
                'sig',
                'country_from_id',
                'region_from_id',
                'area_from_id',
                'city_from',
                'country_to_id',
                'city_to',
                'date_from',
                'body_type_id',
                'body_type_group_id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.edit
             */
            ->setMethod('myGruzEdit', new Method('my.gruz.edit', [
                'id',
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.trans.edit
             */
            ->setMethod('myGruzEdit', new Method('my.trans.edit', [
                'id',
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.list
             */
            ->setMethod('myGruzList', new Method('my.gruz.list', [
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.get
             */
            ->setMethod('myGruzGet', new Method('my.gruz.get', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.trans.get
             */
            ->setMethod('myTransGet', new Method('my.trans.get', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.list.short
             */
            ->setMethod('myGruzListShort', new Method('my.gruz.list.short', [
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.delete
             */
            ->setMethod('myGruzDelete', new Method('my.gruz.delete', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.trans.delete
             */
            ->setMethod('myTransDelete', new Method('my.trans.delete', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.trans.delete
             */
            ->setMethod('myTransDelete', new Method('my.trans.delete', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.trash.list
             */
            ->setMethod('myGruzTrashList', new Method('my.gruz.trash.list', [
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.trash.get
             */
            ->setMethod('myGruzTrashGet', new Method('my.gruz.trash.get', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.trans.trash.get
             */
            ->setMethod('myTransTrashGet', new Method('my.trans.trash.get', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.trash.list.short
             */
            ->setMethod('myGruzTrashListShort', new Method('my.gruz.trash.list.short', [
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.trash.delete
             */
            ->setMethod('myGruzTrashDelete', new Method('my.gruz.trash.delete', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.trans.trash.delete
             */
            ->setMethod('myTransTrashDelete', new Method('my.trans.trash.delete', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.trans.trash.delete
             */
            ->setMethod('myTransTrashDelete', new Method('my.trans.trash.delete', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.trash.return
             */
            ->setMethod('myGruzTrashReturn', new Method('my.gruz.trash.return', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.gruz.refresh
             */
            ->setMethod('myGruzRefresh', new Method('my.gruz.refresh', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.trans.refresh
             */
            ->setMethod('myTransRefresh', new Method('my.trans.refresh', [
                'sig',
                'id',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/distance.calc
             */
            ->setMethod('distanceCalc', new Method('distance.calc', [
                'sig',
                'towns',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/distance.search
             */
            ->setMethod('distanceSearch', new Method('distance.search', [
                'sig',
                'name',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.message.dialog.get
             */
            ->setMethod('myMessageDialogGet', new Method('my.message.dialog.get', [
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.message.dialog.get.contents
             */
            ->setMethod('myMessageDialogGetContents', new Method('my.message.dialog.get.contents', [
                'sig',
                'roomId',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.message.dialog.set.read
             */
            ->setMethod('myMessageDialogSetRead', new Method('my.message.dialog.set.read', [
                'sig',
                'roomId',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.message.dialog.delete
             */
            ->setMethod('myMessageDialogDelete', new Method('my.message.dialog.delete', [
                'sig',
                'roomId',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.message.send
             */
            ->setMethod('myMessageSend', new Method('my.message.send', [
                'sig',
                'roomId',
                'text',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/my.message.room.id.get
             */
            ->setMethod('myMessageRoomIdGet', new Method('my.message.room.id.get', [
                'sig',
                'refId',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/get.payment.form.ref
             */
            ->setMethod('getPaymentFormRef', new Method('get.payment.form.ref', [
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/get.payment.moment.ref
             */
            ->setMethod('getPaymentMomentRef', new Method('get.payment.moment.ref', [
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/get.payment.unit.ref
             */
            ->setMethod('getPaymentUnitRef', new Method('get.payment.unit.ref', [
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/get.payment.valuta.ref
             */
            ->setMethod('getPaymentValutaRef', new Method('get.payment.valuta.ref', [
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/body.type
             */
            ->setMethod('bodyType', new Method('body.type', [
                'sig',
            ]))
            /**
             * @link http://api.lardi-trans.com/doc/body.type.group
             */
            ->setMethod('bodyTypeGroup', new Method('body.type.group', [
                'sig',
            ]));
    }
}