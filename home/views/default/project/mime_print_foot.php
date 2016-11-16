 <OBJECT style="display:none;"  ID="jatoolsPrinter" CLASSID="CLSID:B43D3361-D075-4BE2-87FE-057188254255"
                  codebase="jatoolsPrinter.cab#version=8,6,0,0"></OBJECT> 
  
<div class="center" style="margin:0 auto;text-align:center;margin-top:10px;">
    <input class="btn btn-primary" onclick="doPrint('打印预览...')" type="button" value="打印预览..."> &nbsp;&nbsp;
</div>
<div class="alert alert-warning alert-dismissable" style="width:40%;margin:0 auto;text-align:left;font-family:'微软雅黑';">
<strong>温馨提示</strong>
1、第一次使用，请下载打印控件，解压后安装。<a href="/data/download/jastool/setup.zip" target="_blank">点击下载</a><br> 
2、请使用带有IE内核的浏览器进行打印，360浏览器请使用兼容模式
</div>
<script>
  function doPrint(how) {
    myDoc = {
        settings:{paperName:'a4',
        orientation:1,
        leftmargin:3},   // 选择a4纸张进行打印
        documents: document,
        /*
         要打印的div 对象在本文档中，控件将从本文档中的 id 为 'page1' 的div对象，
         作为首页打印id 为'page2'的作为第二页打印            */
        copyrights: '杰创软件拥有版权  www.jatools.com' // 版权声明,必须   
    };
    if(how == '打印预览...')
          document.getElementById("jatoolsPrinter").printPreview(myDoc );   // 打印预览
    else if(how == '打印...')
          document.getElementById("jatoolsPrinter").print(myDoc ,true);   // 打印前弹出打印设置对话框
    else 
          document.getElementById("jatoolsPrinter").print(myDoc ,false);       // 不弹出对话框打印
    // jatoolsPrinter.print(myDoc, false); // 直接打印，不弹出打印机设置对话框 
  }
 </script>