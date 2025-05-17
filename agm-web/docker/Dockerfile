FROM node:18-alpine as build-stage

WORKDIR /app

# Định nghĩa ARG để nhận biến môi trường từ docker-compose
ARG BUILD_ENV=development

# Copy package files
COPY package*.json ./

# Clear npm cache and install dependencies
RUN npm cache clean --force && \
    npm install && \
    npm install esbuild@0.24.2 --save-dev

# Copy project files
COPY . .

# Đổi tên file môi trường phù hợp
RUN if [ "$BUILD_ENV" = "production" ]; then \
      echo "Building for production environment"; \
      cp .env.production .env.local; \
    else \
      echo "Building for development environment"; \
      cp .env.development .env.local; \
    fi

# Build với mode tương ứng với môi trường
RUN if [ "$BUILD_ENV" = "production" ]; then \
      npm run build -- --mode production; \
    else \
      npm run build -- --mode development; \
    fi

# Production stage
FROM nginx:alpine as production-stage
COPY --from=build-stage /app/dist /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]