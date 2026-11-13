# Lemma 2.81

## What the project is

Lemma 2.81 is a lemma-first lexical database. It stores concepts and lemmas, and it derives the word forms. A verb yields every form it takes. The headwords come from the BNC/COCA 5k word frequency list that Paul Nation compiled at Victoria University of Wellington.

Each headword carries a classification from the Princeton WordNet lexname and hypernym tree. The lexname gives a broad semantic field. The hypernym tree gives a narrower topic.

The records feed a flashcard trainer. Each record carries its morphological family, its sense definition and its derived forms.

## How to run it

Run every command from the repository root. The entry file in showroom/ reads `LEMMA_BASE` to reach autoload.php, so export the variable first.

```
export LEMMA_BASE=/lemma/
php -S 127.0.0.1:8115 -t showroom showroom/index.php
```

The runner in tests/ executes the suite.

```
php tests/run.php
```

## What the routes answer

The router answers the page route and the probe route. The page route / returns the HTML page. The probe route /status returns JSON. The JSON carries the keys `ok`, `lemmas`, `parts` and `derived`. The `lemmas` key holds the record count. The `parts` key holds the counts by part of speech. The `derived` key holds the derived form count. The `ok` key reports the probe status. The bank supplies the counts.

## How the layout is arranged

The file autoload.php loads the classes. The directory src/Home/ holds the model. The file src/Home/Form.php holds one derived form. The form carries its spelling and the ending that produced it. The method apply() puts the ending on a stem. The file src/Home/Lemma.php holds one record. The record carries an id, a concept, a part of speech, a semantic group, a morphological family, a sense definition and derived forms. The file src/Home/Bank.php holds the records. The bank looks a record up by any spelling it derives. The bank groups records by morphological family and by semantic group. The bank counts records by part of speech. The file src/Home/Sample.php builds the bank. The directory src/App/ holds the router, the front controller and the HTML. The directory showroom/ holds the demo entry file and the stylesheet. The directory tests/ holds the hand-written runner, the assertion helper and the test file. The directory deploy/ holds the systemd unit and the nginx location block.

## The state of the suite

The suite runs 10 checks. The hand-written runner executes them. The assertion helper verifies each result. The command below runs the suite.

```
php tests/run.php
```

## Licence

The repository uses the Mozilla Public License 2.0. The licence lets anyone use, study, change and share the code. A contributor must release changes to covered files under the same licence.
