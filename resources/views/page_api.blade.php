<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap 5, CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">

    <title>Example0001 API</title>
</head>
<body>
    <div class="container py-3">
        <h1 class="text-center text-primary mb-3">API Zoho CRM</h1>
        <div class="shadow rounded border p-3">
            <div class="mb-2 border-bottom">
                <a href="{{ route('generate_refresh_token') }}" class="btn btn-success btn-sm mb-3">Сгенерировать токен доступа и обновления</a>
                <div class="alert alert-primary m-0" role="alert">
                    Чтобы сгенерировать токен доступа и обновления, необходимо указать данные:<br>
                    - code<br>
                    - client_id<br>
                    - client_secret<br>
                    в контроллере ExecutiveController (метод: generate_refresh_token() ).
                </div>
            </div>

            <div class="my-3 border-top"></div>

            <div class="">
                <div class="alert alert-primary m-0">
                    Статус обновления токена: <strong>{{ $status_token }}</strong>
                </div>
            </div>

            <div class="my-3 border-top"></div>

            @if($status)
                <div class="alert alert-secondary m-0">
                    <div class="text-bark">Получение списка записей</div>
                    <div>Кол-во записей: <strong>{{ count($list_deals) }}</strong></div>

                    <div class="w-50 mx-auto my-3">
                        <h3 class="text-center">Создать сделку</h3>
                        <form action="{{ Route('add_deals') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Сделка - имя</label>
                                <input class="form-control" type="text" placeholder="*" name="Deal_Name">
                            </div>
                            <div>
                                <button class="btn btn-success">Создать</button>
                            </div>
                        </form>
                    </div>

                    <div class="w-50 mx-auto my-3">
                        <h3 class="text-center">Создать задачу</h3>
                        <form action="{{ Route('add_task') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Тема</label>
                                <input class="form-control" type="text" placeholder="*" name="name_task">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Название сделки</label>
                                <input class="form-control" type="text" placeholder="*" name="name_deal">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">ID сделки</label>
                                <input class="form-control" type="text" placeholder="*" name="id_deal">
                            </div>
                            <div>
                                <button class="btn btn-success">Создать</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="my-3 border-top"></div>

                <div class="alert alert-secondary m-0">
                    <h4>Deals list</h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Create date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list_deals as $item)
                                <tr>
                                    <th scope="row">{{ $item['id'] }}</th>
                                    <td>{{ $item['Deal_Name'] }}</td>
                                    <td>{{ $item['Created_Time'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
