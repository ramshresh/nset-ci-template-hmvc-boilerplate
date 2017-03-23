#!/usr/bin/env python
activate_this = "/vagrant/env/bin/activate_this.py"
execfile(activate_this, dict(__file__=activate_this))


print "Content-type: text/html\n"
print "ok"