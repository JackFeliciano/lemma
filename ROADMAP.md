# Roadmap

The project today holds its model in src/Home/ and its router in src/App/. The demo sits in showroom/, the 10 checks sit in tests/, and the systemd unit and the nginx location block sit in deploy/. The headwords come from the BNC/COCA 5k list, and the model derives every word form. Each headword carries a classification from the Princeton WordNet lexname and hypernym tree. The records feed a flashcard trainer. A contributor could add the capabilities below.

1. **A storage layer.** The bank keeps every record in memory today. A store on disk would hold the records between requests, and the work would touch src/Home/Bank.php, src/Home/Sample.php and the front controller in src/App/.

2. **Write routes.** The router answers the page route and the probe route today. Routes that create, edit and delete records would touch the router, the front controller and the HTML in src/App/.

3. **An importer.** Sample.php builds the bank record by record today. An importer that reads a word list file would touch src/Home/Sample.php and src/Home/Lemma.php.

4. **Search.** The bank finds a record by any spelling it derives today. Search over sense definitions and semantic groups would touch src/Home/Bank.php and the HTML in src/App/.

5. **An export route.** A flashcard trainer consumes the records today. A route that returns every record as JSON would touch the router in src/App/ and src/Home/Bank.php.
