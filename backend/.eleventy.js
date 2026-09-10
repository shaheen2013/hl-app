const markdownIt = require("markdown-it");
const syntaxHighlight = require("@11ty/eleventy-plugin-syntaxhighlight");
const pluginMermaid = require("@kevingimbel/eleventy-plugin-mermaid");

module.exports = function(eleventyConfig) {
  //add plugins for syntaxHighlighting and mermaid
  eleventyConfig.addPlugin(syntaxHighlight);
  eleventyConfig.addPlugin(pluginMermaid);

  //set markdownIt config
  eleventyConfig.setLibrary(
    "md",
    markdownIt({
      html: true,
      breaks: true,
      linkify: true
    })
  );

  // create a "docs" collection from all the mardown files , ordered by orderPath
  eleventyConfig.addCollection("docs", collection => {
    const _collection = collection
      .getFilteredByGlob("**/*.md")
      .sort((a, b) =>
        (a.data.orderPath || a.filePathStem).localeCompare(
          b.data.orderPath || b.filePathStem
        )
      );

    // _collection.forEach((entry) => {
    //   const path = entry.inputPath.split("/");

    //   entry.data.lv = path.length - 2;

    //   entry.data.group && entry.data.lv--;
    // });

    return _collection;
  });

  return {
    dir: {
      includes: "eleventy/_includes", // where the root file is
      input: ".", //all files but we just use markdown
      output: "docs" //output directory
    }
  };
};
