<p>Already registered? <a href="/login">Login</a></p>
<form id="registration_form" action="/submit_registration_form" method="POST">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="email" name="email" placeholder="Email address">
    <input type="text" name="first_name" placeholder="First Name">
    <input type="text" name="last_name" placeholder="Last Name">
    <input type="password" name="password" placeholder="Password">
    <input type="submit" value="Register">
    
    <p class="erorr_message hidden"></p>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $('body')
            .on('submit', '#registration_form', function(e){
                e.preventDefault();
                let registration_form = $('#registration_form');

                if(registration_form.find('input[type=email]').val() != '' && registration_form.find('input[type=password]').val() != ''){
                    $.post(registration_form.attr('action'), registration_form.serialize(), function(is_registered){
                        if(is_registered.status)
                            window.location = '/dashboard';
                        else
                            registration_form.find('.erorr_message').removeClass('hidden').text(is_registered.message);
                    }, 'json');
                }
                else
                    registration_form.find('.erorr_message').removeClass('hidden').text('Please enter your details.');
            });
    });
</script>