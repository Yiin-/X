<form action="{{ route('auth.login') }}" method="POST" class="form form__auth">
    {!! csrf_field() !!}
    <h1>Login</h1>
    <label>Username</label>
    <input type="text" name="username">
    <label>Password</label>
    <input type="password" name="password">
    <input type="hidden" name="site_address" value="{{ $account }}">
    <button type="submit">Login</button>
</form>

<style lang="scss" scoped>
  .form {
    width: 400px;
    background: $color-white;
    box-shadow: $box-shadow;
    margin: 0 auto;
    padding: 50px 50px 100px;
  }

  label {
    font-size: 12px;
    font-weight: $font-weight-semibold;
  }

  input {
    width: 100%;
    display: block;
    padding: 5px 10px;
    font-size: 16px;
    border: 1px solid lighten($color-main-dark, 50%);
    margin: 5px 0;
  }

  button {
    background: $color-main;
    color: $color-text-alt;
    border: none;
    padding: 10px 20px;
    width: 100%;
  }
</style>