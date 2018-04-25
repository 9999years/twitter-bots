A repo for my terrible twitter bots! Each bot is in a directory of its handle,
i.e. [@gardensbot] is in the `gardens` directory.

Work to make this code better and migrate it to this repository is ongoing, so
don’t be surprised if these don’t quite work yet. In particular, moving each bot
to a separate directory is new, so includes, particularly for the Twitter API
file, will probably not work. Additionally, logging is... lacking. Currently,
log files are never cleared, so they just kinda grow and grow.

# Requirements

Generally, PHP7 with Curl. But I wouldn’t be surprised if these were moved to
Python in the future...

# License

AGPLv3 for now, but I’d like to move to a non-profit license in the future.

[@gardensbot]: https://twitter.com/gardensbot
