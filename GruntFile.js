module.exports = function(grunt) {
  // Configuration
  grunt.initConfig({
    // compile scss -> css
    sass: {
      options: {
        includePaths: ['bower_components/bootstrap-sass/assets/stylesheets'],
      },
      // scss compile options for development
      dev: {
        options: { style: 'expanded', },
        files: {
          'public/assets/app.css': 'scss/app.scss',
        },
      },
      // scss compile options for distribution
      dist: {
        options: { style: 'compressed', },
        files: {
          'public/assets/app.min.css': 'scss/app.scss',
        },
      },
    },

    // Watch specifiy files and run tasks again if changes
    // in the files where detected
    watch: {
      grunt: { files: ['GruntFile.js'] },

      sass: {
        files: 'assets/scss/**.scss',
        tasks: ['sass:dev'],
      }
    },

    // copy needed bower files
    copy: {
      main: {
        files: [
          { expand: true, cwd: 'bower_components/bootstrap-sass/assets',             src: ['fonts/**'],         dest: 'public/assets/' },
          { expand: true, cwd: 'bower_components/bootstrap-sass/assets/javascripts', src: ['bootstrap.min.js'], dest: 'public/assets/' },
          { expand: true, cwd: 'bower_components/jquery/dist',                       src: ['jquery.min.js'],    dest: 'public/assets/' },
        ],
      },
    }
  });

  // Load plugins
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-sass');

  // Set tasks
  grunt.registerTask('build', ['copy', 'sass']);
  grunt.registerTask('default', ['build', 'watch']);
}
