<p>Not a user? <a href="/register">Register</a></p>
<form id="login_form" action="/submit_login_form" method="POST">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="email" name="email" placeholder="Email address">
    <input type="password" name="password" placeholder="Password">
    <input type="submit" value="Login">
    
    <p class="erorr_message hidden"></p>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $('body')
            .on('submit', '#login_form', function(e){
                e.preventDefault();
                let login_form = $('#login_form');

                if(login_form.find('input[type=email]').val() != '' && login_form.find('input[type=password]').val() != ''){
                    $.post(login_form.attr('action'), login_form.serialize(), function(is_login){
                        if(is_login.status)
                            window.location = '/dashboard';
                        else
                            login_form.find('.erorr_message').removeClass('hidden').text(is_login.message);
                    }, 'json');
                }
                else
                    login_form.find('.erorr_message').removeClass('hidden').text('Please enter your details.');
            });
    });
</script>