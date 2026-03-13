FROM node:20

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

CMD ["sh", "-c", "npm install && npm run dev -- --host 0.0.0.0"]