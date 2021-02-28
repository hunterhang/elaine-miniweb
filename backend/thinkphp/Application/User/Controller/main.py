# -*- coding: utf-8 -*-
# filename: main.py
import web
from handle import Handle
urls = (
		'/User/Index/sys_notice', 'Handle',
)

if __name__ == '__main__':
	app = web.application(urls, globals())
	app.run()
