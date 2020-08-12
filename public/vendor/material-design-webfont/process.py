css = open('css/materialdesignicons.css')
out = open('out.html', 'w')
for line in css:
    if ':before' in line:
        cls = line[1:line.find(":")]
        outline ='<span><i class="mdi '+cls+'"></i> '+cls+'</span>\n'
        out.write(outline)
out.close()
