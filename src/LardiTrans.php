<?php

namespace MammutAlex\LardiTrans;

/**
 * Class LardiTrans
 *
 * @author  Alex Kovalchuk alex@cargofy.com
 * @package MammutAlex\LardiTrans
 */
class LardiTrans
{
    /**
     * Уникальный номер, аналог PHP сессии
     *
     * @access private
     * @var string
     */
    private $sig;

    /**
     * Основной номер фирмы
     *
     * @access private
     * @var string
     */
    private $uid;

    /**
     * API клиент
     *
     * @access private
     * @var ApiClient
     */
    private $apiClient;

    /**
     * Конструктор устанавливает API клиента
     */
    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    /**
     * Получение внутренней переменной sig
     *
     * @return string
     */
    public function getSig(): string
    {
        return $this->sig;
    }

    /**
     * Задает новое значения для внутренней переменной sig
     *
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
     * Получение внутренней переменной sig
     *
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * Задает новое значения для внутренней переменной uid
     *
     * @param string $uid
     *
     * @return LardiTrans
     */
    public function setUid(string $uid): self
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * Отправка запроса на сервер
     *
     * @param string $method     назва метода
     * @param array  $parameters параметры
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/reference
     */
    public function sendMethod(string $method, array $parameters = []): array
    {
        return $this->apiClient->sendRequest($method, array_merge([
            'sig' => $this->sig,
            'uid' => $this->uid,
        ], $parameters));
    }

    /**
     * Тестовая комманда, для проверки работоспособности и т.д.
     *
     * @param string $text Слово, для проверки правильности восприятия русских букв
     *
     * @return array Ответ сервера в формате JSON
     *
     * @link http://api.lardi-trans.com/doc/test
     */
    public function sendTest(string $text = 'Привет'): array
    {
        return $this->sendMethod('test', ['test_text' => $text]);
    }

    /**
     * Тестовая комманда, для проверки sig-идентификатора
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/test.sig
     */
    public function sendTestSig(): array
    {
        return $this->sendMethod('test.sig');
    }

    /**
     * Функция для авторизации, если не произошло ошибки, то устанавливает {@link $sig} и {@link $uid}
     *
     * @param string $login    Логин в системе lardi-trans.com
     * @param string $password Пароль или хеш пароля
     * @param bool   $isHash   MD5 {@link $password} сумма пароля или пароль в открытом виде
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/auth
     */
    public function sendAuth(string $login, string $password, bool $isHash = false): array
    {
        $data = $this->sendMethod('auth', [
            'login' => $login,
            'password' => $isHash ? $password : md5($password)
        ]);
        $this->setSig($data['sig']);
        $this->setUid($data['uid']);
        return $data;
    }

    /**
     * Получение партнеров
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/partners.info.get
     */
    public function sendPartnersInfoGet(): array
    {
        return $this->sendMethod('partners.info.get');
    }

    /**
     * Метод установки статуса пользователя
     *
     * @param string $status текстовый статус пользователя (максимальная длина 255 символов)
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/user.set.status
     */
    public function sendUserSetStatus(string $status): array
    {
        return $this->sendMethod('user.set.status', [
            'status' => $status
        ]);
    }

    /**
     * Получить список стран и областей
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/base.country
     */
    public function sendBaseCountry(): array
    {
        return $this->sendMethod('base.country');
    }

    /**
     * Получить список типов автомобилей
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/base.auto_tip
     */
    public function sendBaseAutoTip(): array
    {
        return $this->sendMethod('base.auto_tip');
    }

    /**
     * Получить список типов загрузки
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/base.zagruz
     */
    public function sendBaseZagruz(): array
    {
        return $this->sendMethod('base.zagruz');
    }

    /**
     * Добавление заявки по грузам
     *
     * @param array $parameters Параметры
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.add
     */
    public function sendMyGruzAdd(array $parameters): array
    {
        return $this->sendMethod('my.gruz.add', $parameters);
    }

    /**
     * Добавление заявки по транспорту
     *
     * @param array $parameters Параметры
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.trans.add
     */
    public function sendMyTransAdd(array $parameters): array
    {
        return $this->sendMethod('my.trans.add', $parameters);
    }

    /**
     * Редактирование заявки по грузам
     *
     * @param int   $id         ID заявки для редактирования
     * @param array $parameters Параметры
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.edit
     */
    public function sendMyGruzEdit(int $id, array $parameters): array
    {
        return $this->sendMethod('my.gruz.edit', array_merge($parameters, [
            'id' => $id
        ]));
    }

    /**
     * Редактирование заявки по транспорту
     *
     * @param int   $id         ID заявки для редактирования
     * @param array $parameters Параметры
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.trans.edit
     */
    public function sendMyTransEdit(int $id, array $parameters): array
    {
        return $this->sendMethod('my.trans.edit', array_merge($parameters, [
            'id' => $id
        ]));
    }

    /**
     * Получение списка "Мои грузы/транспорт"
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.list
     */
    public function sendMyGruzList(): array
    {
        return $this->sendMethod('my.gruz.list');
    }

    /**
     * Получение одной заявки по грузам
     *
     * @param int $id ID заявки
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.get
     */
    public function sendMyGruzGet(int $id): array
    {
        return $this->sendMethod('my.gruz.get', [
            'id' => $id
        ]);
    }

    /**
     * Получение одной заявки по транспорту
     *
     * @param int $id ID заявки
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.trans.get
     */
    public function sendMyTransGet(int $id): array
    {
        return $this->sendMethod('my.trans.get', [
            'id' => $id
        ]);
    }

    /**
     * Получение списка "Мои грузы/транспорт" в сокращенном варианте
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.list.short
     */
    public function sendMyGruzListShort(): array
    {
        return $this->sendMethod('my.gruz.list.short');
    }

    /**
     * Удалить свой груз
     *
     * @param int $id ID груза который нужно удалить
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.delete
     */
    public function sendMyGruzDelete(int $id): array
    {
        return $this->sendMethod('my.gruz.delete', [
            'id' => $id
        ]);
    }

    /**
     * Удалить свой транспорт
     *
     * @param int $id ID транспорта который нужно удалить
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.trans.delete
     */
    public function sendMyTransDelete(int $id): array
    {
        return $this->sendMethod('my.trans.delete', [
            'id' => $id
        ]);
    }

    /**
     * Получение списка корзины "Мои грузы/транспорт"
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.trash.list
     */
    public function sendMyGruzTrashList(): array
    {
        return $this->sendMethod('my.gruz.trash.list');
    }

    /**
     * Получение одной заявки по грузам из корзины
     *
     * @param int $id ID заявки
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.trash.get
     */
    public function sendMyGruzTrashGet(int $id): array
    {
        return $this->sendMethod('my.gruz.trash.get', [
            'id' => $id
        ]);
    }

    /**
     * Получение одной заявки по транспорту из корзины
     *
     * @param int $id ID заявки
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.trans.trash.get
     */
    public function sendMyTransTrashGet(int $id): array
    {
        return $this->sendMethod('my.trans.trash.get', [
            'id' => $id
        ]);
    }

    /**
     * Получение списка корзины "Мои грузы/транспорт" в сокращенном варианте
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.trash.list.short
     */
    public function sendMyGruzTrashListShort(): array
    {
        return $this->sendMethod('my.gruz.trash.list.short');
    }

    /**
     * Удалить свой груз из корзины
     *
     * @param int $id ID груза который нужно удалить из корзины
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.trash.delete
     */
    public function sendMyGruzTrashDelete(int $id): array
    {
        return $this->sendMethod('my.gruz.trash.delete', [
            'id' => $id
        ]);
    }

    /**
     * Удалить свой транспорт из корзины
     *
     * @param int $id ID транспорта который нужно удалить из корзины
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.trans.trash.delete
     */
    public function sendMyTransTrashDelete(int $id): array
    {
        return $this->sendMethod('my.trans.trash.delete', [
            'id' => $id
        ]);
    }

    /**
     * Извлечь груз из корзины по номеру
     *
     * @param int $id ID груза который нужно восстановить из корзины
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.trash.return
     */
    public function sendMyGruzTrashReturn(int $id): array
    {
        return $this->sendMethod('my.gruz.trash.return', [
            'id' => $id
        ]);
    }

    /**
     * Извлечь транспорт из корзины по номеру
     *
     * @param int $id ID транспорта который нужно восстановить из корзины
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.trans.trash.return
     */
    public function sendMyTransTrashReturn(int $id): array
    {
        return $this->sendMethod('my.trans.trash.return', [
            'id' => $id
        ]);
    }

    /**
     * Повтор заявки по грузам
     *
     * @param int $id ID заявки которую нужно повторить
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.gruz.refresh
     */
    public function sendMyGruzRefresh(int $id): array
    {
        return $this->sendMethod('my.gruz.refresh', [
            'id' => $id
        ]);
    }

    /**
     * Повтор заявки по транспорту
     *
     * @param int $id ID заявки которую нужно повторить
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.trans.refresh
     */
    public function sendMyTransRefresh(int $id): array
    {
        return $this->sendMethod('my.trans.refresh', [
            'id' => $id
        ]);
    }

    /**
     * Расчет расстояния и предложение маршрута
     *
     * @param array $parameters Параметры
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/distance.calc
     */
    public function sendDistanceCalc(array $parameters): array
    {
        return $this->sendMethod('distance.calc', $parameters);
    }

    /**
     * Получение списка возможных городов по части его названия
     *
     * @param string $name Город или часть его названия
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/distance.search
     */
    public function sendDistanceSearch(string $name): array
    {
        return $this->sendMethod('distance.search', [
            'name' => $name
        ]);
    }

    /**
     * Получение диалогов
     *
     * @param array $parameters Параметры
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.message.dialog.get
     */
    public function sendMyMessageDialogGet(array $parameters = []): array
    {
        return $this->sendMethod('my.message.dialog.get', $parameters);
    }

    /**
     * Получение всех сообщений диалога
     *
     * @param string $roomId ID диалога
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.message.dialog.get.contents
     */
    public function sendMyMessageDialogGetContents(string $roomId): array
    {
        return $this->sendMethod('my.message.dialog.get.contents', [
            'roomId' => $roomId
        ]);
    }

    /**
     * Установить статус диалога "прочитан/не прочитан", в зависимости от флага read.
     * Возвращает список диалогов, для которых устанавливался статус, с их статусами
     *
     * @param string $roomId ID диалога
     * @param bool   $status статус, в который нужно перевести диалоги, по умолчанию диалоги установятся, как
     *                       прочитанные
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.message.dialog.set.read
     */
    public function sendMyMessageDialogSetRead(string $roomId, bool $status = true): array
    {
        return $this->sendMethod('my.message.dialog.set.read', [
            'roomId' => $roomId,
            'read' => $status,
        ]);
    }

    /**
     * Удалить диалог
     *
     * @param string $roomId ID диалога
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.message.dialog.delete
     */
    public function sendMyMessageDialogDelete(string $roomId): array
    {
        return $this->sendMethod('my.message.dialog.delete', [
            'roomId' => $roomId,
        ]);
    }

    /**
     * Отправить сообщение
     *
     * @param string $roomId ID диалога
     * @param string $text   Текст сообщения
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.message.send
     */
    public function sendMyMessageSend(string $roomId, string $text): array
    {
        return $this->sendMethod('my.message.send', [
            'roomId' => $roomId,
            'text' => $text,
        ]);
    }

    /**
     * Получить id комнаты для партнера/Cоздание групповой комнаты
     *
     * @param string $refId RefId партнера, для которго необходимо получить roomId, если указать более одного refId
     *                      будет создана групповая комната
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/my.message.room.id.get
     */
    public function sendMyMessageRoomIdGet(string $refId): array
    {
        return $this->sendMethod('my.message.room.id.get', [
            'refId' => $refId,
        ]);
    }

    /**
     * Получить список форм оплаты
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/get.payment.form.ref
     */
    public function sendGetPaymentFormRef(): array
    {
        return $this->sendMethod('get.payment.form.ref');
    }

    /**
     * Получить список моментов оплаты
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/get.payment.moment.ref
     */
    public function sendGetPaymentMomentRef(): array
    {
        return $this->sendMethod('get.payment.moment.ref');
    }

    /**
     * Получить список единиц оплаты
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/get.payment.unit.ref
     */
    public function sendGetPaymentUnitRef(): array
    {
        return $this->sendMethod('get.payment.unit.ref');
    }

    /**
     * Получить список валют
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/get.payment.valuta.ref
     */
    public function sendGetPaymentValutaRef(): array
    {
        return $this->sendMethod('get.payment.valuta.ref');
    }

    /**
     * Получить список типов кузова
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/body.type
     */
    public function sendBodyType(): array
    {
        return $this->sendMethod('body.type');
    }

    /**
     * Получить список групп типов кузова
     *
     * @return array Ответ сервера в формате JSON
     * @link http://api.lardi-trans.com/doc/body.type.group
     */
    public function sendBodyTypeGroup(): array
    {
        return $this->sendMethod('body.type.group');
    }
}