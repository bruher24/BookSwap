$('#dropFiltersBtn').on('click', () => {
    $('#filtersForm .btn-check').each(function () {
        const checkbox = $(this)[0];

        checkbox.checked = false;

        $(checkbox).trigger('change');
    });

    $('#filtersForm').submit();
});

