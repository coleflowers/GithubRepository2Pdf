#!/usr/bin/php
<?php
if ($argc == 1) {
    echo "param is err" . PHP_EOL;
    exit;
}

$body = '';

for ($i = 1; $i < $argc; $i++) {
    $file = $argv[$i];
    $info = pathinfo($file);
    $name = $info['basename'];

    $lan = lan($info['extension']);

    // $dirNew  = $info['dirname'] . '_new';
    // $newFile = $dirNew . '/' . $name;

    if ($name == '.DS_Store') {
        continue;
    }

    $file = str_replace('_', '\\_', $file);

    $tpl = <<<str
\\section*{{$file}}
\\lstinputlisting[language={$lan}]{"$file"}
str;
    $body .= PHP_EOL . $tpl . PHP_EOL;
}

$tex = head();
$tex .= $body;
$tex .= foot();

file_put_contents('src.tex', $tex);

echo 'Done!' . PHP_EOL;
exit;

function lan($ext)
{
    $map         = [];
    $map['php']  = 'PHP';
    $map['c']    = 'C';
    $map['cpp']  = 'C++';
    $map['h']    = 'C++';
    $map['m']    = 'C';
    $map['java'] = 'Java';
    $map['el']   = 'elisp';
    $map['tex']  = 'TeX';
    $map['py']   = 'Python';
    $map['go']   = 'Go';
    $map['html'] = 'HTML';

    return $map[$ext] ?? 'txt';
}

function foot()
{
    $str = <<<str

\\end{document}

str;
    return $str;
}

function head()
{
    $str = <<<str

\documentclass{article}
\usepackage[hmargin=0in,vmargin=0in]{geometry}
\usepackage{listings}
\usepackage{color}

% For better handling of unicode (Latin characters, anyway)
% 书签
\IfFileExists{lmodern.sty}{\usepackage{lmodern}}{}
\usepackage[T1]{fontenc}
\usepackage[utf8]{inputenc}

\lstset{
    numbers=left,                   % where to put the line-numbers
    numberstyle=\small \ttfamily \color[rgb]{0.4,0.4,0.4},
    stepnumber=2,
    numbersep=5pt,
                % style used for the linenumbers
    showspaces=false,               % show spaces adding special underscores
    showstringspaces=false,         % underline spaces within strings
    showtabs=false,                 % show tabs within strings adding particular underscores
    %frame=lines,                    % add a frame around the code
    tabsize=4,                        % default tabsize: 4 spaces
    breaklines=true,                % automatic line breaking
    breakatwhitespace=false,        % automatic breaks should only happen at whitespace
    %basicstyle=\ttfamily,
    %identifierstyle=\color[rgb]{0.3,0.133,0.133},   % colors in variables and function names, if desired.
    keywordstyle=\color[rgb]{0.133,0.133,0.6},
    commentstyle=\color[rgb]{0.133,0.545,0.133},
    stringstyle=\color[rgb]{0.627,0.126,0.941},
}

\begin{document}

str;
    return $str;
}