# PHP Easy Journal

**About**

- **PHP Easy Journal** is a minimalist **free PHP micro blogging script** to post info snippets. No database required.

**Audience**

- This script may not be suitable for novice users or those without at least a basic understanding of HTML, CSS, JS, etc. There is no point and click to do things.

- You are required to enter the full sources of your posts yourself, which may seem *mission impossible*, but provides a maximum level of flexibility. 

- However, if all you want or need is a simple way to publish text only, you shouldn't have any trouble.

**Configuration**

- Open the script and edit the **USER CONFIG** section to match your environment.

    - `$ejn_path` => server path
    - `$ejn_fold` => script folder
    - `$ejn_data` => data folder
    - `$ejn_edit` => editor token
    - `$ejn_lang` => language identifier
    - `$ejn_blog` => blog title
    - `$ejn_mdes` => blog description
    - `$ejn_mkey` => blog keywords

**Editing**

- You can add new entries or edit old ones by calling the script with the editor token set in `$ejn_edit`, e.g. `/easy-journal/?edit`

    - Press the **Post** button to post a new entry.

    - Press the **Update** button to update modified old entries.

    - Press the **Quit** button to close the editor screen.

**Content**

- Title and text may contain pretty much everything you'd put in a stock HTML file. Be it fancy formatting, images, audio, etc. In fact, any entry could easily mimick an entire micro site in its own right.

- Just make sure your sources are valid. The script is really quite straightforward and doesn't waste time with exotic syntax checking. It will publish whatever you throw at it; so don't shoot the messenger.

**Translation**

- The default locale is in `lang/en.php`. Just copy that file and edit the string values to your likes. Next, change `$ejn_lang` to match the language identifier, e.g. `$ejn_lang = "de";` to print messages in German, as defined in `lang/de.php`. Feel free to submit your translation to be included in the package.

**License**

- **PHP Easy Journal** is published under the terms of the GPL v3. Please leave the script's reference URL in *"Powered by..."* intact AS IS.
