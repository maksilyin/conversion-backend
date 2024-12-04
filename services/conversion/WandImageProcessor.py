from wand.image import Image

def can_convert(input_file: str, output_format: str) -> bool:
    try:
        with Image(filename=input_file) as img:
            img.format = output_format.upper()  # Устанавливаем целевой формат
            img.make_blob()  # Проверяем, можно ли создать бинарный объект в этом формате
        return True
    except Exception as e:
        print(f"Conversion error: {e}")
        return False

# Проверяем возможность конвертации
input_file = "example.jpg"
output_format = "png"

if can_convert(input_file, output_format):
    print(f"Conversion from {input_file} to {output_format} is supported!")
else:
    print(f"Conversion from {input_file} to {output_format} is NOT supported.")
