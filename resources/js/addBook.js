import $ from 'jquery';

$(function () {
    let authors = [];
    let selectedAuthors = [];
    $('#addBookBtn').click(function() {
        authorsListRequest().then(function (response){
            authors = JSON.parse(response);
            console.log(authors)
            authors.forEach((author) =>{
                const option = `<option value="${author.id}">${author.fullName}</option>`;
                $('#floatingAuthorId').append(option);
            });
        })

    });

    let oldValue;

    $('#floatingAuthorId').on('focus', function (){
        oldValue = this.value;
    }).change(function(){
        if (typeof this.value !== 'string') {
            const index = selectedAuthors.indexOf(oldValue);
            if (index > -1) {
                selectedAuthors.splice(index, 1);
            }
            selectedAuthors.push(this.value);
        }
        console.log(selectedAuthors);
    });

    $('#addAuthorBtn').click(function(){
        const selector = $('.authorDiv');

        const count = selector.length;
        if (count >= 3) {
            alert('Добавлен максимум авторов!');
            return;
        }

        const authorDiv = selector.last();
        const newDiv = authorDiv.clone();
        const firstId = selector.first().children('select').first().attr('id');
        let newId = firstId + count;
        newDiv.children('select').attr('id', newId);
        newDiv.children('label').html('Дополнительный автор');
        newDiv.children('label').attr('for', newId);

        authorDiv.after(newDiv);
    });



    $('#saveBookBtn').click(function () {
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
    });
}

async function authorsListRequest()
{
    return await $.ajax({
        url: '/authors/',
        type: 'get',
        async: true,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
    });
}