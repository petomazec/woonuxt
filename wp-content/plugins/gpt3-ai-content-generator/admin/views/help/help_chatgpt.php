<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<script>
  jQuery( function() {
    jQuery( "#accordion8" ).accordion({
      collapsible: true,
      heightStyle: "content"
    });
  } );
</script>
<div id="help_tabs-8">
  <div id="accordion8">
  <h3>ChatGPT</h3>
        <div>
          <p>We are offering ChatGPT like chatbot for your website.</p>
          <p>There are two different methods to add a chatbot to your website.</p>
          <p>1. You can add a chatbot to your website by using a <mark class="wpcgai_container_help_mark">shortcode</mark>.</p>
          <p>2. You can add a chatbot to your website by using a <mark class="wpcgai_container_help_mark">widget</mark>.</p>
          <p>So what are the differences between these two methods?</p>
          <p>1. By using a shortcode, you can choose which page you want to add a chatbot to.</p>
          <p>2. By using a widget, you can add a chatbot to all pages of your website.</p>
        </div>
    <h3>Shortcode</h3>
        <div>
          <p>To add a chatbot to your website by using a shortcode, you need to follow these steps:</p>
          <p>1. Go to the page you want to add a chatbot to.</p>
          <p>2. Copy following shortcode and paste it into the page content.</p>
          <p><code>[wpaicg_chatgpt]</code></p>
          <p>3. Save the page.</p>
          <p>4. Go to the page you added the chatbot to and check if the chatbot is working.</p>
        </div>
    <h3>Widget</h3>
        <div>
          <p>To add a chatbot to your website by using a widget, you need to follow these steps:</p>
          <p>1. Go to <b>Settings → ChatGPT</b>.</p>
          <p>2. Enable the widget.</p>
          <p>3. Select a location for the widget.</p>
          <p>4. Save the settings.</p>
          <p>4. Go to the page you added the chatbot to and check if the chatbot is working.</p>
        </div>
    <h3>Customization</h3>
        <div>
          <p>You can customize the shortcode from <b>Main Menu → ChatGPT</b>.</p>
          <p>You can customize the widget from <b>Settings → ChatGPT</b>.</p>
          <p>Here you can change the following settings:</p>
          <p>1. <b>Language</b>: You can change the language of the chatbot. We support total of 38 languages.</p>
          <p><i>Arabic, Bulgarian, Chinese, Croatian, Czech, Danish, Dutch, English, Estonian, Filipino, Finnish, French, German, Greek, Hebrew, Hindi, Hungarian, Indonesian, Italian, Japanese, Korean, Latvian, Lithuanian, Malay, Norwegian, Polish, Portuguese, Romanian, Russian, Serbian, Slovak, Slovenian, Spanish, Swedish, Thai, Turkish, Ukrainian and Vietnamese.</i></p>
          <p>2. <b>Tone</b>: You can change the tone of the chatbot. We support total of 6 tones. They help you to make your chatbot more human-like.</p>
          <p><i>Friendly, Proffesional, Sarcastic, Humorous, Cheerful and Anecdotal.</i></p>
          <p>3. <b>Act As</b>: You can change the act as of the chatbot. We support more than 30 proffesion. You can give your chatbot a proffesion.</p>
          <p><i>Accountant, Advertising Specialist, Architect, Artist, Blogger, Business Analyst, Business owner, Car Expert, Consultant, Counselor, Cryptocurrency Expert, Cryptocurrency Trader, Customer Support, Dentist, Designer, Dietitian, Digital Marketing Agency, Editor, Engineer, Event Planner, Financial advisor, Freelancer, Insurance Agent, Insurance Broker, Interior Designer, Journalist, Lawyer, Marketing Agency, Marketing Expert, Marketing Specialist, Nurse, Pharmacist, Photographer, Physician, Programmer, Publisher, Public Relations Agency, Real Estate Agent, Recruiter, Reporter, Sales Person, Sales Representative, SEO Agency, SEO Expert, Social Media Agency, Technical support, Teacher, Therapist, Travel Agency, Trainer, Veterinarian, Videographer, Web Design Agency, Web Design Expert, Web Designer, Web Developer, Web Development Agency, Web Development Expert, Writer.</i></p>
          <p>4. <b>Parameters</b>: You can change the AI engine parameters of the chatbot.</p>
          <p>The following table provides an overview of the parameters that are used by the chatbot.</p>
            <ol>
                <li><b>Temperature</b> - Controls the amount of randomness. Higher values will result in more random completions. Lower values will result in more predictable completions. The default value is 0.7. It can be set to a value between 0 and 1. <a href="https://gptaipower.com/gpt-3-temperature-settings/" target="_blank">Learn more</a>.</li>
                <li><b>Max Tokens</b> - The maximum number of tokens to generate. The default value is 1300. It can be set to a value between 1 and 1400. <a href="https://gptaipower.com/max-tokens/" target="_blank">Learn more</a>.</li>
                <li><b>Top P</b> - Controls the diversity of the generated text. Lower values will result in more predictable completions. Higher values will result in more random completions. The default value is 1.0. It can be set to a value between 0 and 1. <a href="https://gptaipower.com/top_p/" target="_blank">Learn more</a>.</li>
                <li><b>Best Of</b> - The number of different completions to return. The default value is 1. It can be set to a value between 1 and 20. <a href="https://gptaipower.com/best-of/" target="_blank">Learn more</a>.</li>
                <li><b>Frequency Penalty</b> - Controls the frequency of the generated text. Lower values will result in more frequent completions. Higher values will result in less frequent completions. The default value is 0.01. It can be set to a value between 0 and 2. <a href="https://gptaipower.com/frequency-penalty/" target="_blank">Learn more</a>.</li>
                <li><b>Presence Penalty</b> - Controls the frequency of the generated text. Lower values will result in more frequent completions. Higher values will result in less frequent completions. The default value is 0.01. It can be set to a value between 0 and 2. <a href="https://gptaipower.com/presence-penalty/" target="_blank">Learn more</a>.</li>
            </ol>
          <p>5. <b>Custom Text</b>: You can customize the text of the chatbot.</p>
            <ol>
                <li><b>AI Name</b> - You can change the name of the AI. Default is AI.</li>
                <li><b>You</b> - You can change the name of the user. Default is You.</li>
                <li><b>AI Thinking</b> - You can change the text of the AI thinking while it is generating a response. Default is AI Thinking.</li>
                <li><b>Placeholder</b> - You can change the placeholder text of the input field. Default is Type a message.</li>
                <li><b>Welcome Message</b> - You can change the welcome message of the chatbot. Default is Hello human, I am a GPT3 powered AI chat bot. Ask me anything!</li>
                <li><b>No Answer Message</b> - For some reason, the chatbot could not generate a response. You can change the message that will be displayed. Default is empty.</li>
            </ol>
            <p>6. <b>Context</b>: You can add context to the chatbot. This is the most important part.</p>
            <ol>
                <li><b>Remember Conversation</b> - You can enable or disable the conversation memory. If you enable this option, the chatbot will remember the conversation and will be able to continue the conversation from where it left off.</li>
                <li><b>Remember Conversation Up To</b> - You can set the number of messages that the chatbot will remember. Default is 10. It can be set to a value between 3 and 20.</li>
                <li><b>Content Aware</b> - You can enable or disable the content aware. If you enable this option, the chatbot will be able to generate a response based on the content of your website. We have two options for this. You can choose one of them.</li>
                <li><p><b>Use Excerpt</b> - If you enable this option, the chatbot will be able to generate a response based on the excerpt of your pages.</p>
                <p>Excerpt is an optional text associated to a Post. Most of the time, it is used as the Post summary.</p>
                <p>You can set the excerpt from here: <b>Pages → All Pages → Edit Page → Excerpt</b>.</p>
                <p>If Excerpt is empty, WordPress automatically creates an excerpt using the first 55 words of the post.</p>
                <p>So when a user visit a page on your website, the chatbot will be aware of the content of the page and will be able to generate a response based on the content of the page.</p>
                <p>It is important to make sure that the excerpt of your pages is unique and relevant.</p></li>
                <li><p><b>Use Embeddings</b> - This feature requires Pincecone integration. If you enable this option, the chatbot will be able to generate a response based on the content of your pages via embeddings.</p>
                <p>Embeddings are a way to represent text in a vector space.</p>
                <p>Our plugin use the Pinecone vector database as our "external knowledge base" — like *long-term memory for GPT-3.</p>
                <p>When a user enters a query in the chat box, our plugin uses OpenAI embeddings to convert the query into a vector representation. The vector representation is then compared to the vectors of the content on your website using <b>cosine similarity</b>, which measures the angle between two vectors. The content with the highest cosine similarity score is returned as the most relevant result.</p>
                <p>It is a powerful and common combination for building semantic search, question-answering, threat-detection, and other applications that rely on NLP and search over a large corpus of text data.</p>
                <p>To enable this feature please go to <b>Embeddings → Settings</b> and enter your Pinecone API Key and Index.</p>
                <p>You can find more information under the Embeddings tab.</p>
                <p>We have two different methods for Embeddings.</p>
                <p>1. <b>Embeddings + Completions</b> - This method will use the Embeddings to find the most relevant page and then it will use the completions to generate a response. This is the recommended option for chat bot and widget.</p>
                <p>2. <b>Embeddings Only</b> - This method will use the Embeddings to find the most relevant page and then it will use the content of the page to generate a response. It will not use Davinci or any other GPT model for generating a response.</p></li>
                <li><p><b>Nearest Answers Up To (Embeddings Only)</b> - You can set the number of nearest answers that the chatbot will return. Default is 5. It can be set to a value between 1 and 5.</p>
              </li>
            </ol>
            <p>7. <b>Logs</b>: We have three options for chat logs.</p>
            <ol>
              <li><b>Save Chat Logs</b> - You can enable or disable the chat logs. If you enable this option, the chatbot will save the logs of the conversation.</li>
              <li><b>Display Notice</b> - You can enable or disable the notice. If you enable this option, the chatbot will display a notice to the user that the logs are being saved.</li>
              <li><b>Notice Text</b> - You can change the text of the notice. Default is "Please note that your conversations will be recorded".</li>
            </ol>
            <p>8. <b>Style</b>: We have eight options to style the chatbot.</p>
            <ol>
              <li><b>Font Size</b> - You can set the font size of the chatbot. Default is 13. It can be set to a value between 10 and 30.</li>
              <li><b>Font Color</b> - You can set the font color of the chatbot. Default is #000000.</li>
              <li><b>User Background Color</b> - You can set the background color of the user messages. Default is #444654.</li>
              <li><b>AI Background Color</b> - You can set the background color of the user messages. Default is #343541.</li>
              <li><b>Width</b> - You can set the width of the chatbot. Default is 300. It can be set to a value between 200 and 800. You can also specify the width in percentage.</li>
              <li><b>Height</b> - You can set the height of the chatbot. Default is 400. It can be set to a value between 200 and 800. You can also specify the height in percentage.</li>
              <li><b>Use Avatars</b> - You can enable or disable the avatars. If you enable this option, the chatbot will display the avatars of the user and the chatbot. If user is logged in then it will display the avatar of the user.</li>
              <li><b>AI Avatar</b> - You can set the avatar of the chatbot. You can use the default one or you can upload your own avatar. Size must be 40x40.</li>
            </ol>
            </div>
    <h3>Logs</h3>
        <div>
            <p>You can view and search the logs of the chatbot.</p>
            <p>Go to <b>ChatGPT → Logs</b> tab.</p>
            <p>We have following information in the logs.</p>
            <p>1. <b>SessionID</b> - This is the unique ID of the session. Each time a user visits your website and interacts with the chatbot, a new session will be created.</p>
            <p>2. <b>Date</b> - This is the date and time when the user interacted with the chatbot.</p>
            <p>3. <b>User Message</b> - This is the message that the user sent to the chatbot.</p>
            <p>4. <b>AI Response</b> - This is the response that the chatbot generated.</p>
            <p>5. <b>Page</b> - This is the title of the page where the user is currently interacting with the chatbot.</p>
            <p>6. <b>Source</b> - This is the source of interaction whether it is a shortcode or a widget.</p>
            <p>7. <b>Action</b> - You can view full conversation.</p>
            <p><b>Note:</b> OpenAI's usage policy requires that automated systems (including conversational AI and chatbots) disclose to users that they are interacting with an AI system.</p>
            <p>Additionally, it's important to note that conversations may be recorded for quality assurance and training purposes.</p>
            <p>If this is the case, it is important to obtain user consent and comply with relevant regulations such as the General Data Protection Regulation (GDPR) in the European Union.</p>
            <p>You should also update your privacy policy to inform users of the data that is being collected and how it is being used.</p>
            <p>Please check OpenAI's <a href="https://platform.openai.com/docs/usage-policies" target="_blank">Usage Policy</a> for more information.</p>
        </div>
  </div>
</div>