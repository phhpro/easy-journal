# PHP Easy Journal

**PHP Easy Journal** is a minimalist **free PHP micro blogging script** to post snippets and other blobs. No database required.

Open the script and edit the `user config` section to match your environment.

You can add new entries or edit old ones by calling the script with the editor token set in `$edit` on line #55.
The default token is `?edit`. Example URL: `http://example.com/easy-journal/?edit`


- Press the `Post` button to post a new entry.

- Press the `Update` button to update modified old entries.

- Press the `Quit` button to close the editor screen.


Header and text may contain pretty much everything you'd put in a stock HTML file. Just make sure your sources are valid. The script doesn't really care to check any of that.
