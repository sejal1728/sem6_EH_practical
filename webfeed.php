<?php
if (isset($_GET["q"])) {
    $q = $_GET["q"];

    if ($q === "Google") {
        $xml = "https://news.google.com/rss";
    } elseif ($q === "ZDN") {
        $xml = "https://www.zdnet.com/news/rss.xml";
    } else {
        echo "Invalid feed selection.";
        exit();
    }

    $xmlDoc = new DOMDocument();
    if (@$xmlDoc->load($xml)) {
        $channel = $xmlDoc->getElementsByTagName('channel')->item(0);
        $channel_title = $channel->getElementsByTagName('title')->item(0)->nodeValue;
        $channel_link = $channel->getElementsByTagName('link')->item(0)->nodeValue;
        $channel_desc = $channel->getElementsByTagName('description')->item(0)->nodeValue;

        echo "<p><a href='$channel_link' target='_blank'>$channel_title</a><br>$channel_desc</p>";

        $items = $xmlDoc->getElementsByTagName('item');
        for ($i = 0; $i < min(3, $items->length); $i++) {
            $item = $items->item($i);
            $item_title = $item->getElementsByTagName('title')->item(0)->nodeValue;
            $item_link = $item->getElementsByTagName('link')->item(0)->nodeValue;
            $item_desc = $item->getElementsByTagName('description')->item(0)->nodeValue;

            echo "<p><a href='$item_link' target='_blank'>$item_title</a><br>$item_desc</p>";
        }
    } else {
        echo "Failed to load RSS feed.";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RSS Feed Reader</title>
    <script>
        function showRSS(str) {
            if (str === "") {
                document.getElementById("rssOutput").innerHTML = "";
                return;
            }
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById("rssOutput").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "?q=" + str, true);
            xmlhttp.send();
        }
    </script>
</head>
<body>

<h2>RSS Feed Reader</h2>
<form>
    <label>Select an RSS-feed:</label>
    <select onchange="showRSS(this.value)">
        <option value="">Select an RSS-feed:</option>
        <option value="Google">Google News</option>
        <option value="ZDN">ZDNet News</option>
    </select>
</form>

<br>
<div id="rssOutput">RSS-feed will be listed here...</div>

</body>
</html>