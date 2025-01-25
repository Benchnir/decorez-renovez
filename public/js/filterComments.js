function filterComments(serviceId, filter)
{
    $.ajax({
        type: 'POST',
        url: '/service/filter-comments',
        data: {
            serviceId: serviceId,
            filter: filter
        },
        success: function(response) {
            $('#comments').html(response);
        }
    });
}

