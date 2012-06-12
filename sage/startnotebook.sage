from sage.server.misc import find_next_available_port
from sagenb.notebook.notebook_object import test_notebook
import urllib, re
port = find_next_available_port(8000, verbose=False)
print port
passwd = str(randint(1,1<<128))
nb = test_notebook(passwd, secure=False, port=port, verbose=True)
nb.set_accounts(True)
