from xml.etree.ElementTree import tostring

from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls
from docx.oxml.shape import CT_Picture
from docx.oxml.xmlchemy import BaseOxmlElement, OneAndOnlyOne

class CT_Anchor(BaseOxmlElement):
    """
    Элемент `<w:anchor>`, контейнер для плавающего изображения.
    """
    extent = OneAndOnlyOne('wp:extent')
    docPr = OneAndOnlyOne('wp:docPr')
    graphic = OneAndOnlyOne('a:graphic')

    @classmethod
    def _anchor_xml(cls, pos_x, pos_y, wrap_text="largest"):
        """
        Стиль переноса текста: `<wp:anchor behindDoc="0">`;
        Положение изображения: `<wp:positionH relativeFrom="page">`;
        Обтекание текста: `<wp:wrapSquare wrapText="largest"/>`.
        """
        if wrap_text is None:
            wrap = '<wp:wrapNone/>'
        else:
            wrap = f'<wp:wrapSquare wrapText="{wrap_text}"/>'
        return (
            '<wp:anchor behindDoc="1" distT="0" distB="0" distL="0" distR="0"'
            ' simplePos="0" layoutInCell="1" allowOverlap="1" relativeHeight="2" locked="0"'
            f' {nsdecls("wp", "a", "pic", "r")}>'
            '  <wp:simplePos x="0" y="0"/>'
            '  <wp:positionH relativeFrom="page">'
            f'    <wp:posOffset>{int(pos_x)}</wp:posOffset>'
            '  </wp:positionH>'
            '  <wp:positionV relativeFrom="page">'
            f'    <wp:posOffset>{int(pos_y)}</wp:posOffset>'
            '  </wp:positionV>'
            '  <wp:extent />'
            '  <wp:effectExtent l="0" t="0" r="25400" b="12700"/>'
            f' {wrap}'
            '  <wp:docPr />'
            '  <wp:cNvGraphicFramePr>'
            '    <a:graphicFrameLocks noChangeAspect="1"/>'
            '  </wp:cNvGraphicFramePr>'
            '  <a:graphic>'
            '    <a:graphicData>'
            '    </a:graphicData>'
            '  </a:graphic>'
            '</wp:anchor>'
        )

    @classmethod
    def new(cls, cx, cy, shape_id, pic, pos_x, pos_y):
        """
        Возвращает новый элемент `<wp:anchor>`, заполненный
        переданными значениями в качестве параметров.
        """
        anchor = parse_xml(cls._anchor_xml(pos_x, pos_y, None))
        anchor.extent.cx = cx
        anchor.extent.cy = cy
        anchor.docPr.id = shape_id
        anchor.docPr.name = f'Picture {shape_id}'
        anchor.graphic.graphicData.uri = (
                'http://schemas.openxmlformats.org/drawingml/2006/picture')
        anchor.graphic.graphicData._insert_pic(pic)
        return anchor

    @classmethod
    def new_pic_anchor(cls, shape_id, rId, filename, cx, cy, pos_x, pos_y):
        """
        Возвращает новый элемент `wp:anchor`, содержащий элемент
        `pic:pic` задается значениями аргументов.
        """
        pic_id = 0  # Word, похоже, не использует это, но и не опускает его
        pic = CT_Picture.new(pic_id, filename, rId, cx, cy)
        anchor = cls.new(cx, cy, shape_id, pic, pos_x, pos_y)
        anchor.graphic.graphicData._insert_pic(pic)
        return anchor
