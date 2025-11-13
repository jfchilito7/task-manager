# Usamos Node 20 (versión ligera)
FROM node:20-alpine

# Directorio de trabajo
WORKDIR /app

# Copiamos los archivos de dependencias
COPY package*.json ./

# Instalamos dependencias
RUN npm install

# Copiamos el resto del código (aunque usaremos volúmenes para desarrollo)
COPY . .

# Exponemos el puerto de Vite
EXPOSE 5173

# Comando para iniciar (con --host para que Docker lo vea)
CMD ["npm", "run", "dev", "--", "--host"]