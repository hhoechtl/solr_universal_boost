Solr universal boost
====================

> Assign a list of boosting keywords to every indexable record type

## What does it do?

If solr is already properly installed and configured for indexing it extends the TCA of every indexed record by a
special field which allows to add a set of keywords.
Those keywords will be indexed and boost the record on all the keywords.

You want a specific page or news appear on a search keyword, but the record doesn't contain this keyword or doesn't
score high enough to appear on the first result page?
This Extension is the solution.

## Requirements

This extension is built using TYPO3 6.2. Older versions are not tested.

You need EXT:solr version 3.* installed, configured and running. The additional keywords field will just be visible if the records are already indexed.

## Installation

Download and install the extension. Add typoscript to static includes on root template: `Apache Solr - Universal Boost`

## Settings

The extension provides just one setting:

	plugin.tx_solr.search.relevance.keywordboost = 40

This value influences how high the added keywords score compared to the other indexed fields.
You can try out how high this value needs to be set to get the desired result.

A value of 100 will result in a sticky behaviour of the record for the specified keyword.
A value of 10 will just be as if the keyword would appear in the regular content of the record.

## Internals

It adds a TCA column for every item_type which is available in `tx_solr_indexqueue_item`. This trick is necessary because when the TCA is loaded I couldn't think of a better and simpler solution for this.

Because it didn't want to extend every database table with a field I hook into *tcemain* to grab the additional column, store the values in a seperate table. (Of course the other way round for loading data)

The keywords set produce a field inside the indexed solr documents called `boostkeywords_stringM`. This field is added to the list of *queryFields* configured in the solr extension with the boosting factor configured as mentioned above.

I overwrite the default ScoreAnalyzer which comes with EXT:solr because this field wouldn't be evaluated and a backend-user couldn't figure out why a documents scores that high when using the ScoreAnalyzer.

## TODO

- check multilingual behaviour

## Known issues

- Keywords field can not be used when creating a new record, just editing records (uid needs to be set)