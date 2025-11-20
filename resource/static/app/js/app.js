$(function () {

    $('.hashRequest').on('click', function () {
        var button = $(this);
        var url = button.data('url');
        var type = button.data('type');
        var mob = button.data('mob');
        var wait = button.data('wait');
        if (mob !== undefined && mob !== "") {
            var mobVal = $('.' + mob).val();
        } else {
            var mobVal = '';
        }
        if (wait !== undefined && wait !== "") {
            var countdown = wait;
        } else {
            var countdown = 60;
        }
        if (type === '') {
            alert('验证码类型必须提交');
        }

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                type: type,
                mobile: mobVal
            },
            success: function (data) {
                if (data.state == 'success') {
                    button.prop('disabled', true);
                    var interval = setInterval(function () {
                        button.text(countdown + '秒后可重发');
                        countdown--;

                        if (countdown < 0) {
                            clearInterval(interval);
                            button.text('获取验证码').prop('disabled', false);
                        }
                    }, 1000);
                } else {
                    alert(data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                button.prop('disabled', false);
            }
        });
    });

    $('.ajaxLink').click(function (e) {
        e.preventDefault();
        var _this = $(this);
        var url = _this.data('url');
        var method = _this.data('method') || 'GET';
        var message = _this.data('message') || '确定执行该操作吗？';

        if (confirm(message)) {
            $.ajax({
                url: url,
                type: method,
                success: function (data) {
                    if (data.state == 'success') {
                        window.location.reload();
                    }else{
                        alert(data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });
})