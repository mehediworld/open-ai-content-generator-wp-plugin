jQuery(document).ready(function ($) {
    $('#openai-generator-form').submit(function (e) {
        e.preventDefault();

        var prompt = $('#openai-generator-prompt').val();
        $('#openai-generator-result').html('Generating...');

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'generate_content',
                prompt: prompt
            },
            success: function (response) {
                if (response.success) {
                    $('#openai-generator-result').html(response.data);
                } else {
                    $('#openai-generator-result').html('Error: ' + response.data);
                }
            },
            error: function () {
                $('#openai-generator-result').html('An unexpected error occurred.');
            }
        });
    });
});
