<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stream_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('message');
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        Schema::table('streams', function (Blueprint $table) {
            $table->dropColumn(['sdp_offer', 'sdp_answer', 'type']);
            $table->string('stream_key')->nullable()->after('is_live');
            $table->string('rtmp_url')->nullable()->after('stream_key');
            $table->string('hls_url')->nullable()->after('rtmp_url');
            $table->string('thumbnail')->nullable()->after('hls_url');
            $table->string('status')->default('pending')->after('thumbnail');
            $table->integer('viewer_count')->default(0)->after('is_live');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');

        Schema::table('streams', function (Blueprint $table) {
            $table->json('sdp_offer')->nullable();
            $table->json('sdp_answer')->nullable();
            $table->string('type')->default('public');
            $table->dropColumn(['stream_key', 'rtmp_url', 'hls_url', 'thumbnail', 'status', 'viewer_count']);
        });
    }
}
