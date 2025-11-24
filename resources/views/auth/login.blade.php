<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TIXID</title>
</head>
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
<!-- MDB -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
</head>

<body>

    <form class="w-50 d-block mx-auto my-5" method="POST" action="{{route('auth')}}">
        @csrf
        @if (Session::get('success'))
            <div class="alert alert-success my-3">{{Session::get('success')}}</div>
            @endif
            @if (Session::get('error'))
               <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
            <!-- Email input -->
              @error('email')
                <small class="text-danger">{{$message }}</small>
                @enderror
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="email" id="form1Example1" class="form-control @error('email') is-inavalid @enderror" name="email"/>
                <label class="form-label" for="form1Example1">Email address</label>
            </div>

            <!-- Password input -->
                  @error('password')
                    <small class="text-danger">{{$message}}</small>
                @enderror
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" id="form1Example2" class="form-control @error('email') is-inavalid @enderror" name="password"/>
                <label class="form-label" for="form1Example2">Password</label>
            </div>
        <!-- Submit button -->
            <button data-mdb-ripple-init type="submit" class="btn btn-warning btn-block">Log in</button>
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-warning">Kembali</a>
            </div>
        </form>
    </form>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
</body>


</html>
