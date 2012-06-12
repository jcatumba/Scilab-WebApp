# This file was *autogenerated* from the file sage/startnotebook.sage.
from sage.all_cmdline import *   # import sage library
_sage_const_128 = Integer(128); _sage_const_1 = Integer(1); _sage_const_9000 = Integer(9000)
from sage.server.misc import find_next_available_port
from sagenb.notebook.notebook_object import test_notebook
port = find_next_available_port(_sage_const_9000 , verbose=False)
passwd = str(randint(_sage_const_1 ,_sage_const_1 <<_sage_const_128 ))
nb = test_notebook(passwd, secure=False, port=port, verbose=True)