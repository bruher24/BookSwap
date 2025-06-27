import $ from 'jquery';

$(function () {

    $('#addAuthorBtn').click(function(){
        const selector = $('.authorDiv');
        const count = selector.length;
        if (count >= 3) {
            alert('Добавлен максимум авторов!');
            return;
        }
        const authorDiv = selector.last();
        const newDiv = authorDiv.clone();
        newDiv.children('label').html('Дополнительный автор');
        authorDiv.after(newDiv);
    });



    $('#addBookBtn').click(function () {
        const form = $('#addBookForm');
        const formData = getFormData(form);
        console.log(formData)
    });
});

function getFormData(form) {
    const formData = form.serializeArray().reduce(function (obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});

    return formatData(formData);
}

function formatData(formData) {
    return formData;
}

function mainInputs(callback) {
    let selector = '.mainInput';
    let inputs = $(selector);

    inputs.each(function () {
        let property = this.name;
        let val = this.value;
        callback(property, val);
    })
}