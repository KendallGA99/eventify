#!/usr/bin/env python3
"""Renderizar docs/TECNICO.md con ReportLab (herramienta de documentación)."""
from pathlib import Path
import re
from xml.sax.saxutils import escape
from reportlab.lib import colors
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, PageBreak, Table, TableStyle, Flowable
from reportlab.platypus.tableofcontents import TableOfContents

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / 'output/pdf/EVENTIFY_Documento_Tecnico_Borrador.pdf'
OUT.parent.mkdir(parents=True, exist_ok=True)
VERSION = (ROOT / 'VERSION').read_text().strip()
for name, path in [('Body', '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf'), ('BodyBold', '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf')]:
    pdfmetrics.registerFont(TTFont(name, path))
pdfmetrics.registerFontFamily('Body', normal='Body', bold='BodyBold', italic='Body', boldItalic='BodyBold')
styles = getSampleStyleSheet()
styles.add(ParagraphStyle(name='Text', fontName='Body', fontSize=9.4, leading=14, spaceAfter=8))
styles.add(ParagraphStyle(name='Cell', fontName='Body', fontSize=8.3, leading=11.8))
styles.add(ParagraphStyle(name='Section', fontName='BodyBold', fontSize=15, leading=20, spaceBefore=15, spaceAfter=10, keepWithNext=True))
styles.add(ParagraphStyle(name='Subsection', fontName='BodyBold', fontSize=11, leading=15, spaceBefore=11, spaceAfter=7, keepWithNext=True))
styles.add(ParagraphStyle(name='CoverTitle', fontName='BodyBold', fontSize=35, leading=44, textColor=colors.HexColor('#153d4c'), spaceAfter=20))
styles.add(ParagraphStyle(name='CoverSubtitle', fontName='Body', fontSize=19, leading=28, spaceAfter=25))
width = A4[0] - 96


def text(value):
    value = value.replace('—', '-').replace('–', '-')
    value = re.sub(r'`([^`]+)`', r'\1', value)
    return re.sub(r'\*\*(.+?)\*\*', r'<b>\1</b>', escape(value))


class Diagram(Flowable):
    def __init__(self, kind):
        super().__init__()
        self.kind, self.width, self.height = kind, width, 190

    def draw(self):
        c = self.canv
        c.setFont('Body', 9)
        c.setStrokeColor(colors.HexColor('#217c83'))
        def box(x, y, label, boxwidth=110):
            c.setFillColor(colors.HexColor('#edf5f6'))
            c.roundRect(x, y, boxwidth, 42, 5, fill=1)
            c.setFillColor(colors.HexColor('#153d4c'))
            c.drawCentredString(x + boxwidth/2, y + 17, label)
        def edge(x1, y1, x2, y2, label, lx, ly):
            c.line(x1, y1, x2, y2)
            c.setFillColor(colors.HexColor('#153d4c'))
            c.setFont('Body', 8)
            c.drawString(lx, ly, label)
            c.setFont('Body', 9)
        if self.kind == 'architecture':
            for x, label in zip([0, 129, 258, 387], ['Navegador', 'Apache/PHP', 'PDO', 'MySQL']):
                box(x, 120, label, 109)
            for x in [109, 238, 367]:
                edge(x, 141, x+20, 141, '>', x+5, 145)
            box(129, 30, 'API clima PHP')
            box(387, 30, 'Open-Meteo', 109)
            edge(184, 120, 184, 72, 'ruta clima', 190, 91)
            edge(239, 51, 387, 51, 'HTTPS / JSON', 268, 61)
        else:
            for x, y, label in [(0,120,'roles'),(180,120,'users'),(387,120,'reservations'),(0,25,'categories'),(180,25,'events'),(387,25,'tickets')]:
                box(x,y,label,109)
            edge(109,141,180,141,'1 : N',127,150)
            edge(289,141,387,141,'1 : N',317,150)
            edge(109,46,180,46,'1 : N',127,55)
            edge(289,46,387,120,'1 : N',325,76)
            edge(441,120,441,67,'1 : 0..1',446,90)


class Document(SimpleDocTemplate):
    def afterFlowable(self, item):
        if isinstance(item, Paragraph) and item.style.name == 'Section':
            self.notify('TOCEntry', (0, item.getPlainText(), self.page))


def frame(canvas, doc):
    canvas.saveState()
    canvas.setFont('Body', 8)
    canvas.setFillColor(colors.HexColor('#60727a'))
    if doc.page > 1:
        canvas.drawString(48, A4[1]-29, 'EVENTIFY / Documento técnico / ' + VERSION)
    canvas.drawString(48, 27, 'Corte: 09-10-2026 | Pendientes de evaluación indicados')
    canvas.drawRightString(A4[0]-48, 27, str(doc.page))
    canvas.restoreState()


story = [Spacer(1, 75), Paragraph('EVENTIFY', styles['CoverTitle']),
         Paragraph('Documento técnico<br/>Borrador de evaluación', styles['CoverSubtitle']),
         Paragraph('SC-502 Ambiente Web Cliente-Servidor<br/>Código de referencia: ' + text(VERSION) + '<br/>9 de octubre de 2026', styles['Text']),
         Spacer(1, 25), Paragraph('<b>Estado:</b> implementación con verificación parcial. Linux desde ZIP, integración y restauración de datos comprobados. Web pública y controles HTTPS comprobados. Pronóstico público útil comprobado. Windows, Firefox y criterios finales pendientes.', styles['Text']),
         Paragraph('<b>Estudiante:</b> Kendall Gamboa Arce<br/><b>Docente:</b> Rita Robles Loaiza', styles['Text']),
         PageBreak(), Paragraph('Contenido', styles['Subsection'])]
toc = TableOfContents()
toc.levelStyles = [ParagraphStyle(name='Contents', fontName='Body', fontSize=10, leading=17, leftIndent=0)]
story += [toc, PageBreak()]
lines = (ROOT / 'docs/TECNICO.md').read_text().splitlines()
i = 2
while i < len(lines):
    line = lines[i].strip()
    if not line:
        i += 1
        continue
    if line.startswith('```'):
        kind = line[3:]
        i += 1
        while i < len(lines) and not lines[i].startswith('```'):
            i += 1
        story += [Diagram(kind), Spacer(1, 10)]
    elif line.startswith('|'):
        rows = []
        while i < len(lines) and lines[i].strip().startswith('|'):
            cells = [x.strip() for x in lines[i].strip().strip('|').split('|')]
            if not all(re.fullmatch(r'[-: ]+', x) for x in cells):
                rows.append([Paragraph(text(x), styles['Cell']) for x in cells])
            i += 1
        count = len(rows[0])
        fractions = [0.28,0.72] if count == 2 else [0.22,0.31,0.47]
        table = Table(rows, colWidths=[width*f for f in fractions], repeatRows=1, hAlign='LEFT')
        table.setStyle(TableStyle([('BACKGROUND',(0,0),(-1,0),colors.HexColor('#deedf0')),('VALIGN',(0,0),(-1,-1),'TOP'),('GRID',(0,0),(-1,-1),0.35,colors.HexColor('#b8cbd1')),('LEFTPADDING',(0,0),(-1,-1),7),('RIGHTPADDING',(0,0),(-1,-1),7),('TOPPADDING',(0,0),(-1,-1),7),('BOTTOMPADDING',(0,0),(-1,-1),7)]))
        story += [table, Spacer(1,10)]
        continue
    elif line.startswith('## '):
        story.append(Paragraph(text(line[3:]),styles['Section']))
    elif line.startswith('### '):
        story.append(Paragraph(text(line[4:]),styles['Subsection']))
    else:
        story.append(Paragraph(text(line[2:] if line.startswith('- ') else line), styles['Text'], bulletText='-' if line.startswith('- ') else None))
    i += 1
Document(str(OUT), pagesize=A4, rightMargin=48, leftMargin=48, topMargin=50, bottomMargin=48,
         title='EVENTIFY - Documento técnico (borrador)', author='').multiBuild(story, onFirstPage=frame, onLaterPages=frame)
print(str(OUT))
