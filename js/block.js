wp.blocks.registerBlockType('openai/content-generator', {
    title: 'OpenAI Content Generator',
    icon: 'admin-plugins',
    category: 'common',
    edit: function(props) {
        function generateContent() {
            var prompt = document.getElementById('openai-prompt').value;
            
            jQuery.post(ajaxurl, {
                action: 'openai_generate_content',
                prompt: prompt,
                nonce: openai_content_generator.nonce,
            }).done(function(response) {
                document.getElementById('openai-result').innerText = response;
            });
        }
        
        return wp.element.createElement('div', {}, [
            wp.element.createElement('input', { id: 'openai-prompt', type: 'text', placeholder: 'Enter your prompt' }),
            wp.element.createElement('button', { onClick: generateContent }, 'Generate Content'),
            wp.element.createElement('p', { id: 'openai-result' }),
        ]);
    },
    save: function() {
        // Rendering in PHP
        return null;
    },
});
