#!/bin/bash

# Check if a directory is provided
if [ -z "$1" ]; then
    echo "Usage: $0 <directory>"
    exit 1
fi

directory="$1"
output_file="merged.php"

# Remove the output file if it already exists
rm -f "$output_file"

echo "Merging all PHP files from $directory into $output_file..."

# Find and concatenate all PHP files recursively
find "$directory" -type f -name "*.php" | while read -r file; do
    echo "// File: $file" >> "$output_file"
    cat "$file" >> "$output_file"
    echo -e "\n" >> "$output_file" # Add a newline for separation
    echo "Merged: $file"
done

echo "Merge complete! Output saved to $output_file"
