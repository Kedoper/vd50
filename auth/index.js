/*
* Ищем все формы с классом form и добавляем им событие,
* чтобы в дальнейшем обработать форму
* */
document.querySelectorAll('.form')
    .forEach(value =>
        value.addEventListener("submit", formHandler));

/*
*  Функция для отправки запроса.
* Я специально ее вынес как отдельную функцию
* чтобы в дальнейшем постоянно не писать одно и тоже.
*
* В качестве параметра данная функция принимает объект,
* далее этот объект "превращается" в JSON строку и отправляется
* на обработчик.
*
* */
function sendRequest(data) {
    /*
    * Про промисы читайте тут
    * https://learn.javascript.ru/promise
    * */
    return new Promise(resolve => {
        let xr = new XMLHttpRequest(),
            body = JSON.stringify(data);
        /*
        * Первый параметром идет метод запроса, он может
        * быть POST,GET и тд.
        *
        * Вторым параметром идет путь до обработчика,
        * в данном случае обработчик находится в той же директории
        * поэтому в начале мы написали ./ (точка слеш)
        * */
        xr.open('POST', './handler.php');
        /*
        * Тут мы отправляем на обработчик тело запроса
        * (JSON строку)
        * */
        xr.send(body);
        xr.onreadystatechange = function () {
            if (xr.readyState === 4 && xr.status === 200) {
                resolve(JSON.parse(xr.response));
            }
        }

    })
}

/*
*  Это обработчик формы
* */
function formHandler() {
    /*Получаем id формы*/
    let form_type = this.id,
        /*Создаем пустой объект, для отправки на обработчик.
        * Позже мы заполним его данными
        * */
        request_data = {};

    /*Тут мы определяем, в какой форме мы нажали на кнопку*/
    switch (form_type) {
        /*Форма входа*/
        case "login":
            /*Получаем введенные значения из input*/
            let user_login = document.getElementById('user_login').value,
                user_password = document.getElementById('user_password').value;
            /*Заполняем объект.
            * Думаю, тут все понятно.
            * Ключ:Значение
            * */
            request_data = {
                type: form_type,
                user_login: user_login,
                user_password: user_password,
            };
            break;
        /*Форма регистрации*/
        case "register":
            /*Получаем введенные значения из input*/
            let new_user_login = document.getElementById('new_user_login').value,
                new_user_password = document.getElementById('new_user_password').value;
            /*Заполняем объект.
            * Думаю, тут все понятно.
            * Ключ:Значение
            * */
            request_data = {
                type: form_type,
                new_user_login: new_user_login,
                new_user_password: new_user_password,
            };
            break;
    }
    /*Теперь отправляем запрос на обработчик*/
    sendRequest(request_data).then(function (response_data) {
        /* После того, как запрос выполнится, ответ от сервера будет находиться
        * в переменной response_data
        * */

        /*Тут вроде понятно. Просто обращаемся к элементу
        * сообщения формы, чтобы вывести потом како-либо сообщение*/
        let message_box = document.getElementById('form_message');
        /*Нам нужно редактировать текст сообщения, поэтому тут мы обращаемся к
        * тегу <p> в info-block__body.
        *
        * Если приводить аналогию с css, то это будет выглядить так:
        *
        * #form_message>.info-block__body>p
        * */
        let message_box_text = message_box.children[1].children[0];

        /*В зависимости от статуса, выполняем различные действия*/
        switch (response_data.status) {
            /*Если все хорошо*/
            case "success":
                /*Перезагружаем страницу,
                * чтобы попасть на "домашнюю"
                * страницу
                * */
                location.reload();
                break;

            /*
            * Если пароль или логин введен неверно
            * */
            case "wrong":
                /*Тут будем выводить сообщение*/
                message_box_text.innerText = response_data.status_text;
                message_box.classList.remove("hide");
                break;

            /*
            * Пользователь уже существует (при регистрации)
            * */
            case "alreadyExist":
                /*Тут будем выводить сообщение*/
                message_box_text.innerText = response_data.status_text;
                message_box.classList.remove("hide");
                break;
        }
    });
}

function showLoginForm() {
    document.getElementById('login').classList.remove('hide');
    document.getElementById('register').classList.add('hide');
}

function showRegisterForm() {
    document.getElementById('register').classList.remove('hide');
    document.getElementById('login').classList.add('hide');
}