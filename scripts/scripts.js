$(function(){
    //Пагинация
    $('.test-data').find('div:first').show();
    $('.pagination a').on('click',function(){
        if($(this).attr('class') == 'nav-active') return false;
        var link = $(this).attr('href'); //ссылка на текст вкладки
        var prevActive = $('.pagination > a.nav-active').attr('href'); //ссылка активной вкладки

        $('.pagination > a.nav-active').removeClass('nav-active');//удаление класса активной вкладки
        $(this).addClass('nav-active');

        $(prevActive).fadeOut(100, function(){
            $(link).fadeIn(100);
        });
        return false;
    });
    //Отправление результатов
    $('#btn').click(function(){
        var test = +$('#test-id').text();
        var res = {'test':test};
        $('.task').each(function(){
            var id = $(this).data('id');
            var type = $('input[name=task-' + id + ']').attr('type');
            if(type == "radio")
                res[id] = $('input[name=task-' + id + ']:checked').val();//Считывание 
            else
                res[id] = $('input[name=task-'+id+']').val();
        });
        $.ajax({
            url: 'main.php',
            type: 'POST',
            data: res,
            success: function(html){
              $('.content').html(html);
            },
            error: function(){
                alert('Error!');
            }
        });
    });
});