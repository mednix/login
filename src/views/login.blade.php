@section('content')
<form action="{{URL::route('login.process')}}" method="post">
    <fieldset>
        <legend>Login Form :</legend>
        <div class="error">{{$errors->first('msg')}}</div>
    <label for="username">Username</label> <input type="text" name="username" id="username"/>
    <label for="password">Password</label> <input type="text" name="password" id="password"/>
    <button type="submit">Submit</button>
    </fieldset>
</form>
@stop