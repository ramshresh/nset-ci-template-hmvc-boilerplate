#!/usr/bin/env python
activate_this = "/vagrant/env/bin/activate_this.py"
execfile(activate_this, dict(__file__=activate_this))


print "Content-type: text/html\n"
import traceback
import sys
import json
sys.stderr = sys.stdout


print "begin"
try:
    warnings = []
    import csvkit

    print "****************************************************"
    print "OK!"
except:
    print "error"
    print traceback.print_exc()
print "end"

