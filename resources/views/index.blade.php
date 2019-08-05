<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>guest book</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>

    <script src="{{asset('js/echo.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('js/socket.io.js')}}"></script>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

<div class="container-fluid">
    <div class="header d-flex justify-content-around">
        <h3>Гостевая книга</h3>
        <div class="btn-group">
            @if(Auth::check())
                <form action="{{route('logout')}}" method="post">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-primary">Выход</button>
                </form>
            @else
                <button class="btn btn-primary showLogin">Войти/Зарегистрироваться</button>
            @endIf
        </div>
    </div>
    <div class="d-flex justify-content-around">
        <div class="w-25 p-3 mb-2" id="listRecords">
            <div>
                <form action="{{route('new-record')}}" id="newRecordForm" method="post">

                    <div class="form-group">
                        <label for="newRecordContent">Новая запись</label>
                        <textarea type="textarea" name="content" class="form-control" id="newRecordContent"
                                  aria-describedby="emailHelp" placeholder="">

                    </textarea>
                        {{csrf_field()}}
                        <input class="btn btn-primary mt-2" type="submit" value="Отправить">
                    </div>

                </form>
            </div>
            @if(\Illuminate\Support\Facades\Auth::check() &&
            \Illuminate\Support\Facades\Auth::user()->role_id == \App\Repository\Role::ADMIN_ROLE_ID)
                <div>
                    <form action="{{route('csv-record')}}" id="csvForm" method="get">

                        <div class="form-group">
                            <label for="newRecordContent">Дата начала</label>
                            <input type="date" name="begin" id="DateBegin">
                            <label for="newRecordContent">Дата окончания</label>
                            <input type="date" name="end" id="DateEnd">
                            {{csrf_field()}}
                            <input class="btn btn-primary mt-2" type="submit" value="Отправить">
                        </div>

                    </form>
                </div>
            @endIf
            <div id="MediaList">
                @foreach($records as $recordItem)
                    <div class="media" id="record-{{$recordItem->id}}">
                        <div class="media-body">
                            <h5 class="mt-0">{{$recordItem->user->name}}</h5>
                            <span id="content-record-{{$recordItem->id}}">{{$recordItem->content}}</span>
                        </div>
                        @if($recordItem->checkEnableButtons())
                            <div class="btn-group">
                                <button class="showEditForm" data-record-id="{{$recordItem->id}}"> Редактировать
                                </button>
                                <form class="deleteRecordForm"
                                      action="{{route('delete-record', ['id' => $recordItem->id])}}"
                                      method="post">
                                    <input type="hidden" name="id" value="{{$recordItem->id}}">
                                    {{csrf_field()}}
                                    <input type="submit" value="Удалить">
                                </form>
                            </div>
                        @endIf
                    </div>

                @endforeach
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="editRecordModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Редактировать запись</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" class="form-group" id="editRecordForm" method="post">
                    <label for="newRecordContent">Текст записи</label>
                    <div class="form-group  mb-2">
                            <textarea type="textarea" name="content" id="editContentArea" class="form-control"
                                      aria-describedby="emailHelp" placeholder="">
                    </textarea>
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="1">

                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">закрыть</button>
                <button type="button" class="btn btn-primary" id="submitEditRecord">Сохранить</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <form action="{{route('login')}}" method="post" id="login">
                    <div class="form-group">
                        <label for="inputLoginEmail">Email адрес</label>
                        <input type="email" name="email" class="form-control" id="inputLoginEmail"
                               aria-describedby="emailHelp"
                               placeholder="Enter email">
                        {{csrf_field()}}
                    </div>
                    <div class="form-group">
                        <label for="inputLoginPassword">Password</label>
                        <input type="password" class="form-control" name="password" id="inputLoginPassword"
                               placeholder="Password">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" id="toRegistration" class="btn btn-success showRegister">К регистрации</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="formLoginSubmit">Войти</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <form action="{{route('register')}}" method="post" id="registration">
                    <div class="form-group">
                        <label for="inputRegisterEmail">Email адресс</label>
                        <input type="email" name="email" class="form-control" id="inputRegisterEmail"
                               aria-describedby="emailHelp"
                               placeholder="Enter email">
                    </div>

                    <div class="form-group">
                        <label for="inputRegisterEmail">имя</label>
                        <input type="text" name="name" class="form-control"
                               placeholder="Enter name">
                    </div>

                    <div class="form-group">
                        <label for="inputRegisterPassword">Пароль</label>
                        <input type="password" class="form-control" id="inputRegisterPassword" name="password"
                               placeholder="Password">
                    </div>

                    <div class="form-group">
                        <label for="inputRegisterPasswordConfirm">Подтверждение пароля</label>
                        <input type="password" class="form-control" id="inputRegisterPasswordConfirm"
                               name="password_confirmation"
                               placeholder="Password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success showLogin" id="toAuthorisation">к Авторизации
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">закрыть</button>
                <button type="button" class="btn btn-primary" id="submitRegister">Сохранить</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>