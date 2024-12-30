from PIL import Image

image = Image.open("tmp/output-000.png").convert("RGBA")  # Основное изображение
mask = Image.open("tmp/output-001.png").convert("L")  # Маска в черно-белом формате

# Накладываем маску на изображение
image.putalpha(mask)

# Сохраняем изображение с прозрачностью
image.save("tmp/final_image_with_transparency.png")