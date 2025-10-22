#!/bin/bash

# Deploy script for Pittsfield AASR website
# This script copies all website files to the production server using scp

set -e  # Exit on any error

echo "🚀 Deploying Pittsfield AASR website..."

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Configuration
REMOTE_HOST="pittsfield-aasr.org"
REMOTE_PATH="pittsfield-aasr.org"
LOCAL_PATH="."

# Files/directories to exclude from deployment
EXCLUDE_LIST=(
    ".git"
    ".gitignore"
    "deploy.sh"
    "README.md"
    "*.swp"
    "*.tmp"
    ".DS_Store"
    "Thumbs.db"
)

echo -e "${YELLOW}Preparing deployment...${NC}"

# Create a temporary directory for deployment
TEMP_DIR=$(mktemp -d)
echo "Using temporary directory: $TEMP_DIR"

# Copy all files to temp directory
cp -r . "$TEMP_DIR/"

# Remove excluded files/directories from temp directory
echo -e "${YELLOW}Excluding unnecessary files...${NC}"
for exclude in "${EXCLUDE_LIST[@]}"; do
    find "$TEMP_DIR" -name "$exclude" -exec rm -rf {} + 2>/dev/null || true
done

# Show what will be deployed
echo -e "${YELLOW}Files to be deployed:${NC}"
find "$TEMP_DIR" -type f | sed "s|$TEMP_DIR/||" | sort

echo ""
echo -e "${YELLOW}Deploying to ${REMOTE_HOST}:${REMOTE_PATH}...${NC}"

# Deploy using scp
if scp -r "$TEMP_DIR"/* "${REMOTE_HOST}:${REMOTE_PATH}"; then
    echo -e "${GREEN}✅ Deployment successful!${NC}"
    echo -e "${GREEN}Website is now live at: https://pittsfield-aasr.org${NC}"
else
    echo -e "${RED}❌ Deployment failed!${NC}"
    exit 1
fi

# Cleanup
rm -rf "$TEMP_DIR"
echo -e "${YELLOW}Cleanup completed.${NC}"

echo ""
echo -e "${GREEN}🎉 Deployment complete!${NC}"
echo -e "Visit your website at: ${GREEN}https://pittsfield-aasr.org${NC}"