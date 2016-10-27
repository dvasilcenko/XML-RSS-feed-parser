{% for item in feeds %}
    <ul>
        <li><b>Feed title: </b>{{ item.title }}</li>
        <li><b>URL address: </b>{{ item.url }}</li>
        <li><b>Update date and time: </b>{{ item.last_update }}</li>
        <li><b>Articles count: </b>{{ item.articles_count }}</li>
        <li><b>Most recent article’s title: </b>{{ item.recent.title }}</li>
        <li><b>Most recent article’s URL link: </b>{{ item.recent.link }}</li>
    </ul>
{% endfor %}
<a href="index.php">Go back</a>